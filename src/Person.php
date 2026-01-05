<?php

namespace Sxqibo\FastYks;

/**
 * 人员信息接口
 * 
 * 获取指定学校（校区）的用户信息
 * 访问控制: 10次/分
 */
final class Person extends RequestApi
{
    /**
     * 获取人员信息
     * 
     * 请求方式: POST
     * 访问控制: 10次/分
     * 
     * 特别说明：
     * 1. 对接方如果需要开发类似导出功能，建议可以再次调用本接口获取最新人员信息，可以拿到最新人员信息和余额等。而不需要为了拿到实时余额调用单个人员查询接口。
     * 2. 对接方如果想给单个用户提供实时余额，可在用户进入查询余额界面调用单个人员信息查询。
     * 3. 建议定时器(30分钟最佳)调用本接口，增量模式获取人员改变状态信息。
     * 
     * @param int $page 页数，页数从1页开始，不能大于100
     * @param int $limit 分页返回条数，不能大于1000，建议每次获取200-400条
     * @param int $type 人员类型，5:教师或企业员工，6:家长，7:学生
     * @param string $time 最后更新时间，格式：yyyy-MM-dd HH:mm:ss。如果参数存在则会查询时间之后更新的人员信息，如果为空字符串或不存在则会查询全部人员信息。建议使用增量模式，传入最后一条记录的last_update_time
     * @return array 返回人员列表，每个人员包含：
     *   - id: 用户id
     *   - uid: 用户uid
     *   - cust_type: 用户类型（5教师，6家长，7学生）
     *   - sex: 性别
     *   - idcard: 证件号
     *   - cust_name: 姓名
     *   - mobilephone: 用户手机号
     *   - cashMoney: 当前金额
     *   - dayMaxMoney: 日限额
     *   - dayMaxMoneyB: B分组限额
     *   - dayMaxMoneyC: C分组限额
     *   - icCode: 物理卡号
     *   - orgIds: 部门列表
     *   - last_update_time: 最后更新时间
     *   - sign_state: 签约状态 1 已签约
     *   - isZfbFace: 支付宝算法是否采集人脸
     * @throws \Exception
     * 
     * 返回示例:
     * [
     *   {
     *     "uid": "2088812471510971",
     *     "cust_type": 5,
     *     "sex": 1,
     *     "idcard": "513701198912070057",
     *     "cust_name": "何帅",
     *     "id": 18
     *   }
     * ]
     */
    public function getUsers($page = 1, $limit = 200, $type = 7, $time = '')
    {
        // 验证必填参数
        if (empty($this->sid)) {
            throw new \InvalidArgumentException('SID（学校编号）不能为空，请在构造函数中传入或通过 Config::set() 设置');
        }

        // 验证页数
        if ($page < 1 || $page > 100) {
            throw new \InvalidArgumentException('页数参数错误，必须大于等于1且不能大于100');
        }

        // 验证分页条数
        if ($limit < 1 || $limit > 1000) {
            throw new \InvalidArgumentException('分页条数参数错误，必须大于等于1且不能大于1000');
        }

        // 验证人员类型
        if (!in_array($type, [5, 6, 7])) {
            throw new \InvalidArgumentException('人员类型参数错误，只能是 5（教师或企业员工）、6（家长）或 7（学生）');
        }

        $query = [
            'page'  => $page,
            'limit' => $limit,
            'type'  => $type,
            'time'  => $time,
            'sp'    => $this->sp,
            'sid'   => $this->sid
        ];

        $url = $this->url . UriConst::GET_USERS . '?' . http_build_query($query);

        $response = Http::post($url);

        $ret = $this->parseResponse($response);

        return $ret['value'] ?? [];
    }

    /**
     * 获取人员信息（兼容旧版本方法名）
     * 
     * @deprecated 请使用 getUsers 方法
     * @param int $page
     * @param int $limit
     * @param int $type
     * @param string $time
     * @return array
     */
    public function getUser($page, $limit, $type, $time = '')
    {
        return $this->getUsers($page, $limit, $type, $time);
    }

    /**
     * 添加人员信息
     * 
     * 请求方式: POST
     * 请求体: JSON格式
     * 
     * 提示：为了保证数据处理的有效性，每次调用接口，请最少间隔一秒。
     * 
     * @param array $data 人员信息数组，包含以下字段：
     *   - studentName: 姓名（必填）
     *   - deptList: 所属部门数组，例如：['123']，强烈建议一个人员只能在一个部门内（必填）
     *   - job: 职位（必填）
     *   - email: 电子邮箱（必填）
     *   - roleIdList: 所属角色数组，例如：[5,7]，5代表教师，7代表学生（必填）
     *   - idCard: 身份证（必填，为主字段，如果idCard一致，平台不会新增人员会直接返回已有人员ID号）
     *   - phone: 手机号码（必填）
     *   - comeSchoolTime: 入校时间，格式：2019-01-01（必填）
     *   - remarke: 备注（必填）
     *   - sex: 性别，1-男，2-女（必填）
     *   - sub_type: 走读（1）、住读（2）、半住读（3）、半走读（4）、通用（5）（必填）
     *   - couponSelected: 状态，默认填2。1-离职，2-在职（必填）
     *   - parentName: 父（母）姓名（可选）
     *   - parentPhone: 父（母）电话（可选）
     *   - parentIdCard: 父（母）身份证（可选）
     *   - phyCard: 物理卡号，首位去0（可选，如果未传入，仅进行账号添加操作，不会进行开卡操作）
     *   - photo: 图片base64（可选，微信代扣消费、支付宝代扣消费此字段无效，即将废弃字段，建议改为调用同步更新照片接口）
     * @return int|string 返回一脸通系统人员ID
     * @throws \Exception
     * 
     * 返回示例:
     * {
     *   "code": 200,
     *   "message": null,
     *   "value": 6119638
     * }
     * 
     * 备注：
     * 1. 如果物理卡号没有传入此接口，仅进行账号添加操作，不会进行开卡操作
     * 2. 如果add人员时候没有物理卡号参数（phyCard）但是又需要开卡，建议物理卡号参数（phyCard）传入xn开头的卡号，后期可以通过接口更换卡号
     * 3. 此接口为异步接口，如果对时效性有要求，建议物理卡号参数（phyCard）使用同步更新卡号接口
     * 4. 证件号idCard为主字段，如果idCard一致，平台不会新增人员会直接返回已有人员ID号
     */
    public function addPerson($data)
    {
        // 验证必填参数
        if (empty($this->sid)) {
            throw new \InvalidArgumentException('SID（学校编号）不能为空，请在构造函数中传入或通过 Config::set() 设置');
        }

        // 验证必填字段
        $requiredFields = [
            'studentName' => '姓名',
            'deptList' => '所属部门',
            'job' => '职位',
            'email' => '电子邮箱',
            'roleIdList' => '所属角色',
            'idCard' => '身份证',
            'phone' => '手机号码',
            'remarke' => '备注',
            'sex' => '性别',
            'sub_type' => '类型',
            'couponSelected' => '状态'
        ];

        foreach ($requiredFields as $field => $fieldName) {
            if (!isset($data[$field])) {
                throw new \InvalidArgumentException("{$fieldName}（{$field}）不能为空");
            }
            // 对于字符串类型字段，允许空字符串（除了 studentName, idCard, phone）
            if (in_array($field, ['studentName', 'idCard', 'phone']) && $data[$field] === '') {
                throw new \InvalidArgumentException("{$fieldName}（{$field}）不能为空");
            }
        }
        
        // comeSchoolTime 必须存在，但可以为空字符串
        if (!isset($data['comeSchoolTime'])) {
            throw new \InvalidArgumentException('入校时间（comeSchoolTime）字段必须存在（可以为空字符串）');
        }

        // 验证部门列表格式
        if (!is_array($data['deptList']) || empty($data['deptList'])) {
            throw new \InvalidArgumentException('所属部门（deptList）必须是数组且不能为空');
        }

        // 验证角色列表格式
        if (!is_array($data['roleIdList']) || empty($data['roleIdList'])) {
            throw new \InvalidArgumentException('所属角色（roleIdList）必须是数组且不能为空');
        }

        // 验证性别值
        if (!in_array($data['sex'], [1, 2])) {
            throw new \InvalidArgumentException('性别（sex）值错误，只能是 1（男）或 2（女）');
        }

        // 验证类型值
        if (!in_array($data['sub_type'], [1, 2, 3, 4, 5])) {
            throw new \InvalidArgumentException('类型（sub_type）值错误，只能是 1（走读）、2（住读）、3（半住读）、4（半走读）或 5（通用）');
        }

        // 验证状态值
        if (!in_array($data['couponSelected'], [1, 2, 3])) {
            throw new \InvalidArgumentException('状态（couponSelected）值错误，只能是 1（离职）、2（在职）或 3');
        }

        // 验证入校时间格式（允许空字符串，但如果有值则验证格式）
        if (!empty($data['comeSchoolTime']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['comeSchoolTime'])) {
            throw new \InvalidArgumentException('入校时间（comeSchoolTime）格式错误，应为 yyyy-MM-dd 格式，例如：2019-01-01');
        }

        // 构建请求数据
        $requestData = [
            'sp'            => $this->sp,
            'studentName'   => $data['studentName'],
            'deptList'      => $data['deptList'],
            'job'           => $data['job'],
            'email'         => $data['email'],
            'roleIdList'    => $data['roleIdList'],
            'idCard'        => $data['idCard'],
            'phone'         => $data['phone'],
            'comeSchoolTime' => $data['comeSchoolTime'],
            'remarke'       => $data['remarke'],
            'sex'           => (int)$data['sex'],
            'sub_type'      => (int)$data['sub_type'],
            'couponSelected' => (int)$data['couponSelected'],
        ];

        // 添加可选字段
        if (isset($data['parentName']) && $data['parentName'] !== '') {
            $requestData['parentName'] = $data['parentName'];
        }

        if (isset($data['parentPhone']) && $data['parentPhone'] !== '') {
            $requestData['parentPhone'] = $data['parentPhone'];
        }

        if (isset($data['parentIdCard']) && $data['parentIdCard'] !== '') {
            $requestData['parentIdCard'] = $data['parentIdCard'];
        }

        if (isset($data['phyCard']) && $data['phyCard'] !== '') {
            $requestData['phyCard'] = $data['phyCard'];
        }

        if (isset($data['photo']) && $data['photo'] !== '') {
            $requestData['photo'] = $data['photo'];
        }

        $query = [
            'sp'  => $this->sp,
            'sid' => $this->sid
        ];

        $url = $this->url . UriConst::ADD_PERSON . '?' . http_build_query($query);

        $response = Http::post($url, json_encode($requestData, JSON_UNESCAPED_UNICODE), [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ]);

        $ret = $this->parseResponse($response);

        return $ret['value'] ?? '';
    }

    /**
     * 12. 修改人员信息
     *
     * @return array
     */
    public function updatePerson($data)
    {
        // $data = [
        //     'id'             => $this->request->post('id'),
        //     'studentName'    => $this->request->post('student_name'),
        //     'deptList'       => [$this->request->post('dept_list')],
        //     'job'            => $this->request->post('job'),
        //     'email'          => $this->request->post('email'),
        //     'roleIdList'     => [(int)$this->request->post('role_id_list')],
        //     'idCard'         => $this->request->post('id_card'),
        //     'phone'          => $this->request->post('phone'),
        //     'comeSchoolTime' => $this->request->post('come_school_time'),
        //     'remarke'        => $this->request->post('remarke'),
        //     'sex'            => $this->request->post('sex'),
        //     'sub_type'       => $this->request->post('sub_type'),
        //     'couponSelected' => $this->request->post('coupon_selected'),
        //     'parentName'     => $this->request->post('parent_name', ''),
        //     'parentPhone'    => $this->request->post('parent_phone', ''),
        //     'parentIdCard'   => $this->request->post('parent_id_card', ''),
        //     'phyCard'        => $this->request->post('phy_ard', ''),
        //     'photo'          => $this->request->post('photo', ''),
        // ];

        $query = [
            'sp'  => $this->sp,
            'sid' => $this->sid
        ];

        $url = $this->url . UriConst::UPDATE_PERSON . '?' . http_build_query($query);

        $response = Http::post($url, json_encode($data, JSON_UNESCAPED_UNICODE), [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ]);

        $ret = json_decode($response, true);

        if(!$ret){
            exception('更新人员信息异常');
        }

        return $ret['value'] ?? [];
    }

    /**
     * 13. 删除人员信息
     *
     * @return array
     */
    public function deletePerson($personId)
    {
        $data = [
            'teamId'   => $this->sid,
            'personId' => $personId
        ];

        $query = [
            'sp'  => $this->sp,
            'sid' => $this->sid
        ];

        $url = $this->url . UriConst::LIST_DELETE_PERSON . '?' . http_build_query($query);

        $response = Http::post($url, json_encode($data, JSON_UNESCAPED_UNICODE), [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ]);

        $ret = json_decode($response, true);

        if(!$ret){
            exception('删除人员信息异常');
        }

        return $ret['value'] ?? [];
    }

    /**
     * 21. 查询人员信息
     *
     * @return array
     */
    public function queryAccountInfo($cid)
    {
        $query = [
            'custId' => $cid,
            'sp'     => $this->sp,
            'sid'    => $this->sid
        ];

        $url = $this->url . UriConst::QUERY_ACCOUNT_INFO . '?' . http_build_query($query);

        $response = Http::post($url);

        $ret = json_decode($response, true);

        if(!$ret){
            exception('查询人员信息异常');
        }

        return $ret['data'] ?? [];
    }

    /**
     * 22. 查询人员信息(卡号)
     *
     * @return array
     */
    public function queryAccountByIdCode($icCode)
    {
        $query = [
            'icCode' => $icCode,
            'sp'     => $this->sp,
            'sid'    => $this->sid
        ];

        $url = $this->url . UriConst::QUERY_ACCOUNT_BY_IC_CODE . '?' . http_build_query($query);

        $response = Http::post($url);

        $ret = json_decode($response, true);

        if(!$ret){
            exception('查询人员信息异常');
        }

        return $ret['data'] ?? [];
    }

    /**
     * 23. 查询人员信息-证件号
     *
     * @return array
     */
    public function queryAccountByIdcard($idCard)
    {
        $query = [
            'idcard' => $idCard,
            'sp'     => $this->sp,
            'sid'    => $this->sid
        ];

        $url = $this->url . UriConst::QUERY_ACCOUNT_BY_IDCARD . '?' . http_build_query($query);

        $response = Http::post($url);

        $ret = json_decode($response, true);

        if(!$ret){
            exception('查询人员信息异常');
        }

        return $ret['data'] ?? [];
    }

    /*
     * 38.照片换取人员特征
     */
    public function getImageFeatrue($file)
    {
        $filePath = $file->getInfo()['tmp_name'];
        if ($fp = fopen($filePath, "rb", 0)) {
            $gambar = fread($fp, filesize($filePath));
            fclose($fp);
            $base64 = (base64_encode($gambar));
        }

        $query = [
            'sp'  => $this->sp,
            'sid' => $this->sid
        ];
        $data  = [
            'photo' => "data:image/png;base64," . $base64
        ];

        $url = $this->url . UriConst::GET_IMAGE_FEATRUE . '?' . http_build_query($query);

        $response = Http::post($url, json_encode($data, JSON_UNESCAPED_UNICODE), [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ]);

        $ret = json_decode($response, true);

        if(!$ret){
            exception('照片换取人员特征异常');
        }

        return $ret['data'] ?? [];
    }

    /*
     * 35. 同步更新照片接口
     */
    public function updatePersonImage($file, $cid)
    {
        $filePath = $file->getInfo()['tmp_name'];
        if ($fp = fopen($filePath, "rb", 0)) {
            $gambar = fread($fp, filesize($filePath));
            fclose($fp);
            $base64 = (base64_encode($gambar));
        }

        $query = [
            'sp'  => $this->sp,
            'sid' => $this->sid
        ];
        $data  = [
            'custId' => $cid,
            'score' => 1, // 0 不检测照片质量分，1 检测照片质量分
            'photo' => "data:image/png;base64," . $base64
        ];

        $url = $this->url . UriConst::UPDATE_PERSON_IMAGE . '?' . http_build_query($query);

        $response = Http::post($url, json_encode($data, JSON_UNESCAPED_UNICODE), [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ]);

        $ret = json_decode($response, true);

        if(!$ret){
            exception('同步更新照片接口异常');
        }

        if($ret['code'] == 0){
            exception('未识别到人脸');
        }

        if($ret['code'] == 28){
            exception('图片过大');
        }

        return $ret;
    }

    /**
     * 54. 跳转微信刷脸签约
     *
     * @return array
     */
    public function getWxFaceInfoOutSide($cid)
    {
        $query = [
            'cid' => $cid,
            'sp'  => $this->sp,
            'sid' => $this->sid
        ];

        $url = $this->url . UriConst::GET_WX_FACE_INFO_OUT_SIDE . '?' . http_build_query($query);

        $response = Http::post($url);

        $ret = json_decode($response, true);

        if(!$ret){
            exception('获取微信签约参数异常');
        }

        return $ret['data'] ?? [];
    }
}
