<?php
 /*
Update:baoxu 备注：此配置未使用
 */
// ######(以下配置为PM环境：入网测试环境用，生产环境配置见文档说明)#######
// 签名证书路径
const SDK_SIGN_CERT_PATH = 'D:/certs/acp_test_sign.pfx';
// const SDK_SIGN_CERT_PATH = C('UNIONPAY.SDK_SIGN_CERT_PATH');

// 签名证书密码
const SDK_SIGN_CERT_PWD = '000000';
// const SDK_SIGN_CERT_PWD = C('UNIONPAY.SDK_SIGN_CERT_PWD');

// 密码加密证书（这条一般用不到的请随便配）
const SDK_ENCRYPT_CERT_PATH = 'D:/certs/acp_test_enc.cer';
// const SDK_ENCRYPT_CERT_PATH = C('UNIONPAY.SDK_ENCRYPT_CERT_PATH');

// 验签证书路径（请配到文件夹，不要配到具体文件）
const SDK_VERIFY_CERT_DIR = 'D:/certs/';
// const SDK_VERIFY_CERT_DIR = C('UNIONPAY.SDK_VERIFY_CERT_DIR');

// 前台请求地址
const SDK_FRONT_TRANS_URL = 'https://101.231.204.80:5000/gateway/api/frontTransReq.do';
// const SDK_FRONT_TRANS_URL = C('UNIONPAY.SDK_FRONT_TRANS_URL');

// 后台请求地址
const SDK_BACK_TRANS_URL = 'https://101.231.204.80:5000/gateway/api/backTransReq.do';
// const SDK_BACK_TRANS_URL = C('UNIONPAY.SDK_BACK_TRANS_URL');

// 批量交易
const SDK_BATCH_TRANS_URL = 'https://101.231.204.80:5000/gateway/api/batchTrans.do';
// const SDK_BATCH_TRANS_URL = C('UNIONPAY.SDK_BATCH_TRANS_URL');

//单笔查询请求地址
const SDK_SINGLE_QUERY_URL = 'https://101.231.204.80:5000/gateway/api/queryTrans.do';
// const SDK_SINGLE_QUERY_URL = C('UNIONPAY.SDK_SINGLE_QUERY_URL');

//文件传输请求地址
const SDK_FILE_QUERY_URL = 'https://101.231.204.80:9080/';
// const SDK_FILE_QUERY_URL = C('UNIONPAY.SDK_FILE_QUERY_URL');

//有卡交易地址
const SDK_Card_Request_Url = 'https://101.231.204.80:5000/gateway/api/cardTransReq.do';
// const SDK_Card_Request_Url = C('UNIONPAY.SDK_Card_Request_Url');

//App交易地址
const SDK_App_Request_Url = 'https://101.231.204.80:5000/gateway/api/appTransReq.do';
// const SDK_App_Request_Url = C('UNIONPAY.SDK_App_Request_Url');

// 前台通知地址 (商户自行配置通知地址)
const SDK_FRONT_NOTIFY_URL = 'http://localhost:8085/upacp_sdk_php/demo/api_03_wtz/FrontReceive.php';
// const SDK_FRONT_NOTIFY_URL = C('UNIONPAY.SDK_FRONT_NOTIFY_URL');

// 后台通知地址 (商户自行配置通知地址，需配置外网能访问的地址)
const SDK_BACK_NOTIFY_URL = 'http://222.222.222.222/upacp_sdk_php/demo/api_03_wtz/BackReceive.php';
// const SDK_BACK_NOTIFY_URL = C('UNIONPAY.SDK_BACK_NOTIFY_URL');

//文件下载目录 
const SDK_FILE_DOWN_PATH = 'D:/file/';
// const SDK_FILE_DOWN_PATH = C('UNIONPAY.SDK_FILE_DOWN_PATH');

//日志 目录 
const SDK_LOG_FILE_PATH = 'D:/logs/';
// const SDK_LOG_FILE_PATH = C('UNIONPAY.SDK_LOG_FILE_PATH');

//日志级别
const SDK_LOG_LEVEL = 'INFO';
// const SDK_LOG_LEVEL = C('UNIONPAY.SDK_LOG_LEVEL');

?>