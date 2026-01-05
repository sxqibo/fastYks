<?php

namespace Sxqibo\FastYks;

final class UriConst
{
    /**
     * 1. 获取关联组织（校区）信息-V1.6
     */
    const GET_CAMPUS = '/admin-service/adminservice/openApi/getCampus';

    /**
     * 2. 获取组织部门信息
     */
    const GET_DEPARTMENTS = '/admin-service/adminservice/openApi/getDepartments';

    /**
     * 3. 获取设备信息
     */
    const GET_DEVICES = '/admin-service/adminservice/openApi/getDevices';

    /**
     * 4. 获取人员信息
     */
    const GET_USERS = '/admin-service/adminservice/openApi/getUsers';

    /**
     * 5. 获取门禁流水信息
     */
    const GET_DOOR_PASS = '/admin-service/adminservice/openApi/getDoorPass';

    /**
     * 6. 获取消费流水信息
     */
    const GET_CONSUME_BY_SCHOOL = '/admin-service/adminservice/openApi/getConsumeBySchool';

    /**
     * 7. 获取门禁规则信息
     */
    const GET_DOOR_RULES = '/access-service/openApi/getDoorRules';

    /**
     * 8. 获取未绑定设备
     */
    const GET_NOT_BINDING_DEVICE = '/access-service/openApi/getNotBindingDevice';

    /**
     * 9. 保存门禁规则信息
     */
    const SAVE_OR_UPDATE_DOOR_RULE = '/access-service/openApi/saveOrUpdateDoorRule';

    /**
     * 10. 删除门禁规则
     */
    const DEL_DOOR_RULE = '/access-service/openApi/delDoorRule';

    /**
     * 11. 添加人员信息
     */
    const ADD_PERSON = '/admin-service/adminservice/openApi/addPerson';

    /**
     * 12. 修改人员信息
     */
    const UPDATE_PERSON = '/admin-service/adminservice/openApi/updatePerson';

    /**
     * 13. 删除人员信息
     */
    const LIST_DELETE_PERSON = '/admin-service/adminservice/openApi/listDeletePerson';

    /**
     * 14. 添加部门信息
     */
    const ADD_DEPT = '/admin-service/adminservice/openApi/addDept';

    /**
     * 15. 修改部门信息
     */
    const UPDATE_DEPT = '/admin-service/adminservice/openApi/updateDept';

    /**
     * 16. 删除部门信息
     */
    const DELETE_DEPT = '/admin-service/adminservice/openApi/delteDept';

    /**
     * 17. 添加请假信息（废弃）
     */
    const ADD_LEAVE = '/admin-service/adminservice/openApi/addLeave';

    /**
     * 18. 撤销请假信息（废弃）
     */
    const CANCEL_LEAVE = '/admin-service/adminservice/openApi/cancelLeave';

    /**
     * 19. 获取充值流水
     */
    const QUERY_PAY_WATER = '/admin-service/adminservice/openApi/queryPayWater';

    /**
     * 20. 补助流水
     */
    const QUERY_SUBSIDY_INFO = '/admin-service/adminservice/openApi/querySubsidyInfo';

    /**
     * 21. 查询人员信息
     */
    const QUERY_ACCOUNT_INFO = '/admin-service/adminservice/openApi/queryAccountInfo';

    /**
     * 22. 查询人员信息(卡号)
     */
    const QUERY_ACCOUNT_BY_IC_CODE = '/admin-service/adminservice/openApi/queryAccountByIcCode';

    /**
     * 23. 查询人员信息-证件号
     */
    const QUERY_ACCOUNT_BY_IDCARD = '/admin-service/adminservice/openApi/queryAccountByIdcard';

    /**
     * 24. 充值接口
     */
    const CARD_CHARGE = '/admin-service/adminservice/openApi/cardCharge';

    /**
     * 25. 在线扣费（卡号）
     */
    const ONLINE_CARD_CONSUME = '/admin-service/adminservice/openApi/onlineCardConsume';

    /**
     * 26. 线上扣费接口
     */
    const ONLINE_CONSUME = '/admin-service/adminservice/openApi/onlineConsume';

    /**
     * 27. 订单查询接口
     */
    const QUERY_CONSUME_WATER_INFO = '/admin-service/adminservice/openApi/queryConsumeWaterInfo';

    /**
     * 28. 账户挂失、解挂接口
     */
    const LOSS_CARD = '/admin-service/adminservice/openApi/lossCard';

    /**
     * 29. 获取纠错流水
     */
    const QUERY_JC_WATER = '/admin-service/adminservice/openApi/queryJcWater';

    /**
     * 30. 流水纠错接口
     */
    const ERROR_CORRECTION = '/admin-service/adminservice/openApi/errorCorrection';

    /**
     * 31. 插入门禁流水
     */
    const INSERT_ACCESS_RECORD = '/admin-service/adminservice/openApi/insertAccessRecord';

    /**
     * 32. 获取营养成份
     */
    const QUERY_NUTRITIONS = '/order-service/reportOrder/cardApi/queryNutritions';

    /**
     * 33. 通过token获取登录用户信息
     */
    const GET_LOGIN_USER_BY_TOKEN = '/admin-service/adminservice/openApi/getLoginUserByToken';

    /**
     * 34. 通过默认页面menuCode获取管理员以及子管理员
     */
    const GET_MANAGERS = '/admin-service/adminservice/openApi/getManagers';

    /**
     * 35. 同步更新照片接口
     */
    CONST UPDATE_PERSON_IMAGE = '/admin-service/adminservice/openApi/updatePersonImage';

    /**
     * 36. 微信人脸订单支付接口
     */
    const DO_WX_FACE_PAYMENT = '/admin-service/adminservice/openApi/doWxFacePayment';

    /**
     * 37. 微信人脸消费订单查询接口
     */
    const DO_WX_FACE_QUERY = '/admin-service/adminservice/openApi/doWxFaceQuery';

    /**
     * 38. 照片换取特征
     */
    const GET_IMAGE_FEATRUE = '/admin-service/adminservice/openApi/getImageFeatrue';

    /**
     * 39. 请假使用接口-新版
     */

    /**
     * 40. 销假使用接口-新版
     */

    /**
     * 41. 获取错误流水
     */
    const QUERY_ERROR_WATER = '/admin-service/adminservice/openApi/queryErrorWater';

    /**
     * 42. 设置日消费限额
     */
    const UPDATE_DAY_MONEY_BY_CID = '/admin-service/adminservice/openApi/updateDayMoneyByCid';

    /**
     * 43. 添加签约银行卡号
     */
    const INSERT_BANK_CARD = '/admin-service/adminservice/openApi/insertBankCard';

    /**
     * 44. accessToken
     */
    const GET_ACCESS_TOKEN = '/admin-service/adminservice/openApi/getAccessToken';

    /**
     * 45. 基础版请假流水接口（废弃）
     */
    const QUERY_LEAVE_WATER = '/admin-service/adminservice/openApi/queryLeaveWater';

    /**
     * 46. 取款（退款）接口
     */
    const REFUND_MONEY = '/admin-service/adminservice/openApi/refundMoney';

    /**
     * 47. 餐次设置接口
     */
    const HANDLE_DININER = '/admin-service/adminservice/openApi/handleDininer';

    /**
     * 48. 人脸照片删除接口
     */
    const DEL_CUST_FEATURE = '/admin-service/adminservice/openApi/delCustFeature';

    /**
     * 49. 设备信息维护
     */
    const HANDLE_DEVICE = '/admin-service/adminservice/openApi/handleDevice';

    /**
     * 50. 用户小程序使用截止期限
     */
    const UPDATE_PERSON_VALIDITY = '/admin-service/adminservice/openApi/updatePersonValidity';

    /**
     * 51. 补助充值/取款接口
     */
    const GRANT_SUBSIDY = '/admin-service/adminservice/openApi/grantSubsidy';

    /**
     * 52. 注销卡
     */
    const CANCEL_CARD = '/admin-service/adminservice/openApi/cancelCard';

    /**
     * 53. 设置部门管理员(预发布)
     */
    const UPDATE_ORGAN_LEADER = '/admin-service/adminservice/openApi/updateOrganLeader';

    /**
     * 54. 跳转微信刷脸签约
     */
    const GET_WX_FACE_INFO_OUT_SIDE = '/admin-service/adminservice/openApi/getWxFaceInfoOutSide';

    /**
     * 55. 微信垫资还款链接
     */
    const GET_REPAYMENT_URL = '/admin-service/adminservice/openApi/getRepaymenturl';

    /**
     * 56. 跳转微信刷脸集中签约
     */

    /**
     * 57. 配置扣款指定银行卡
     */
    const UPDATE_SCHOOL_CODE = '/admin-service/adminservice/openApi/updateSchoolCode';

    /**
     * 58. 获取微信垫资流水
     */

    /**
     * 59. 获取部门人员信息
     */
    const QUERY_ORGAN_ACCOUNT_INFO = '/admin-service/adminservice/openApi/queryOrganAccountInfo';

    /**
     * 60. 微信垫资还款查询
     */
    const CHECK_BPA_WATER_FOR_OUT_SIZE = '/admin-service/adminservice/openApi/checkBPAWaterForOutSide';

    /**
     * 61. 微信人脸代扣解约
     */
    const DELETE_FACE_RELATION_FOR_OUT_SIDE = '/admin-service/adminservice/openApi/deleteFaceRelationForOutSide';

    /**
     * 62. 获取离线生活码
     */
    const GET_QR_CODE = '/admin-service/adminservice/openApi/getQrCode';

    /**
     * 63. 【家视通】使用期限配置
     */
    const UPDATE_TEL_USER_TIME = '/admin-service/adminservice/openApi/updateTelUserTime';

    /**
     * 64. 【家视通】使用时间配置
     */
    const UPDATE_TEL_USE_MINUTES = '/admin-service/adminservice/openApi/updateTelUseMinutes';

    /**
     * 65. 【家视通】获取通话记录
     */
    const QUERY_TELL_PAY_WATER = '/admin-service/adminservice/openApi/queryTellPayWater';

    /**
     * 66. 【家视通】使用时间批量配置
     */
    const UPDATE_VOIP_USERS_MINUTES = '/admin-service/adminservice/openApi/updateVoipUsesMinutes';

    /**
     * 67. 【家视通】使用期限批量配置
     */
    const UPDATE_VOIP_USERS_TIME = '/admin-service/adminservice/openApi/updateVoipUsersTime';

    /**
     * 68. 【家视通】获取剩余分钟数和使用有效期
     */
    const QUERY_VOIP_USER_TIME = '/admin-service/adminservice/openApi/queryVoipUsertime';

    /**
     * 69. 【家视通】删除联系人
     */
    const DEL_VOIP_RELATION = '/admin-service/adminservice/openApi/delVoipRelation';

    /**
     * 70. 获取品项列表
     */
    const QUERY_ITEM_FILES = '/admin-service/adminservice/openApi/queryItemFiles';

    /**
     * 71. 获取排菜明细
     */
    const QUERY_ARRANGES = '/admin-service/adminservice/openApi/queryArranges';

    /**
     * 72. 获取餐饮订单列表
     */
    const QUERY_ORDER_RECORD = '/admin-service/adminservice/openApi/queryOrderRecord';

    /**
     * 73. 获取餐饮订单品项明细
     */
    const QUERY_ORDER_ITEM = '/admin-service/adminservice/openApi/queryOrderItems';

    /**
     * 74. 获取餐次信息
     */
    const QUERY_DININER = '/admin-service/adminservice/openApi/queryDininer';

    /**
     * 75. 消费机时段汇总数据
     */
    const GET_TERM_TOTAL = '/admin-service/adminservice/openApi/getTermTotal';

    /**
     * 76. 汇总数据
     */
    const QUERY_DALIY_SUMMARY = '/admin-service/adminservice/openApi/queryDaliySummary';

    /**
     * 77. 更换物理卡号
     */
    const PATCH_CARD = '/admin-service/adminservice/openApi/patchCard';

    /**
     * 78. 修改卡类型-个性化接口
     */
    const CHARGE_CARD_TYPE = '/admin-service/adminservice/openApi/chargeCardType';

    /**
     * 79. 查询用户是否已经录入银行卡
     */
    const QUERY_BANK_BY_CID = '/admin-service/adminservice/openApi/queryBankByCid';

    /**
     * 80. 获取商户信息
     */
    const QUERY_MERCHANT_INFO = '/admin-service/adminservice/openApi/queryMerchanInfo';
}
