<?php
DEFINE( '_IS_DEBUG', false );
/*
$tf_ID = "ID";
$tf_id = "id";
$tf_org_code = "org_code";
*/
	$field_org_code = "ORG_CODE";
	$field_multy_lang_code = "MULTY_LANG_CODE";
	$field_white_label_id="WHITE_LABEL_ID";



#
# Structure for the CA_SMS_ACCOUNTS table :
#
	$tableSmsAccounts	= "CA_SMS_ACCOUNTS";
  	$smsAccountsF_Id	= "ID";
  	$smsAccountsF_Quantity = "SMS_QUANTITY";
  	$smsAccountsF_LowLimit = "LOW_SMS_LIMIT";
  	$smsAccountsF_LowReminders = "LOW_SMS_REMINDERS";
  	$smsAccountsF_LowReminderIsSent = "LOW_SMS_REMINDER_IS_SENT";


#
# Structure for the CA_SMS_PAYMENTS table :
#
	$tableSmsPayments	= "CA_SMS_PAYMENTS";
  	$smsPaymentsF_Id	= "ID";
  	$smsPaymentsF_SmsPackage	= "SMS_PACKAGE";
  	$smsPaymentsF_PricePerSms	= "PRICE_PER_SMS";
  	$smsPaymentsF_PaymentTotalAmmount	= "PAYMENT_AMMOUNT";
  	$smsPaymentsF_PaymentTaxAmmount	= "PAYMENT_TAX_AMMOUNT";
  	$smsPaymentsF_PaymentDateTime	= "PAYMENT_DATETIME";
  	$smsPaymentsF_PaymentPartnerId	= "PAYMENT_PARTNER_ID";
  	$smsPaymentsF_PaymentBankId	= "PAYMENT_BANK_ID";
  	$smsPaymentsF_PaymentTransactionId	= "PAYMENT_TRANSACTION_ID";
  	$smsPaymentsF_PaymentStatus	= "PAYMENT_STATUS";



#
# Structure for the CA_TRACKINGS table :
#
	$tableTracking	= "CA_TRACKING_TRACKINGS";
  	$trackingF_Id	= "ID";
    $trackingF_Name	= "NAME";
    $trackingF_CreatedBy  = "CREATED_BY";
    $trackingF_CreationDate  = "CREATION_DATE";
//   $field_org_code = "ORG_CODE";

#
# Structure for the CA_CATEGORIES table :
#
	$tableCategories = "CA_TRACKING_CATEGORIES";
    $categoriesF_TrackingId  = "TRACKING_ID";
    $categoriesF_Name = "NAME";
    $categoriesF_Id = "ID";
  //  $field_org_code = "ORG_CODE";

#
# Structure for the CA_TARGETS table :
#
	$tableTargets = "CA_TRACKING_TARGETS";
	$targetsF_Id = "ID";
    $targetsF_CategoryId = "CATEGORY_ID";
    $targetsF_Name = "NAME";
  //  $field_org_code = "ORG_CODE";

#
# Structure for the CA_CLIENT_CATEGORIES table :
#
	$tableClientCategories = "CA_TRACKING_CLIENT_CATEGORIES";
	$clientCategoriesF_Id = "ID";
    $clientCategoriesF_CategoryId = "CATEGORY_ID";
    $clientCategoriesF_ClientTrackingId = "CLIENT_TRACK_ID";
    $clientCategoriesF_Description = "DESCRIPTION";
   // $field_org_code = "ORG_CODE";

#
# Structure for the CA_CLIENT_TARGETS table :
#
	$tableClientTargets = "CA_TRACKING_CLIENT_TARGETS";
	$clientTargetsF_Id = "ID";
	$clientTargetsF_TargetId = "TARGET_ID";
    $clientTargetsF_ClientCatId = "CLIENT_CAT_ID";
    $clientTargetsF_Progress  = "PROGRES";
    $clientTargetsF_Description = "DESCRIPTON";
  //  $field_org_code = "ORG_CODE";

#
# Structure for the CA_CLIENT_TRACKING table :
#
	$tableClientTracking = "CA_TRACKING_CLIENT_TRACKING";
	$clientTrackingF_Id = "ID";
	$clientTrackingF_StatusId = "STATUS_ID";
    $clientTrackingF_TrackingId  = "TRACKING_ID";
    $clientTrackingF_AppId = "APPOINTMEN_ID";
    $clientTrackingF_ClientId = "CLIENT_ID";
   // $field_org_code = "ORG_CODE";

#
# Structure for the CA_PROPERTIES table :
#
	$tableProperties = "CA_TRACKING_PROPERTIES";
	$propertiesF_Id = "ID";
    $propertiesF_TrackingId  = "TRACKING_ID";
    $propertiesF_Name = "NAME";
    $propertiesF_Value = "VALUE";
   // $field_org_code = "ORG_CODE";

#
# Structure for the CA_STATUSES table :
#
	$tableStatuses = "CA_STATUSES";
	$statusesF_Id = "ID";
    $statusesF_Code = "CODE";
    $statusesF_Name = "NAME";
  //  $field_org_code = "ORG_CODE";

#
# Structure for the CA_APPOINTMENT_TYPES table :
#
	$tableAppTypes = "CA_APPOINTMENT_TYPES";
	$appTypesF_Id = "ID";
    $appTypesF_Time = "TIME";
    $appTypesF_Color = "COLOR";
    $appTypesF_Name = "NAME";
    $appTypesF_AppComment = "APP_COMMENT";
    $appTypesF_MinTime = "MIN_TIME";
    $appTypesF_MaxTime = "MAX_TIME";
    $appTypesF_NumberApp = "NUMBER_APP";
    $appTypesF_Tariff = "TARIFF";
    $appTypesF_IsPublic = "IS_PUBLIC";
    $appTypesF_IsMulty = "IS_MULTY";
    $appTypesF_PeriodStartTime = "AT_PERIOD_START_TIME";
    $appTypesF_PeriodEndTime = "AT_PERIOD_END_TIME";
    $appTypesF_PeriodDay = "AT_PERIOD_DAY";
    $appTypesF_ReminderTime = "REMINDER_TIME";
    $appTypesF_AgeCatID = "AGE_CAT_ID";
    $appTypesF_TariffCurId = "TARIFF_CUR_ID";
    $appTypesF_isShowedDuration = "IS_SHOWED_DURATION";
    $appTypesF_isDisabled = "IS_DISABLED";
    $appTypesF_CODE = "CODE";
    $appTypesF_VAT = "VAT";
	$appTypesF_TariffInfo = "TARIFF_INFO";
  //  $field_org_code = "ORG_CODE";

#
#	Field names which are used as synonims in the next DB tables:
#	CA_DAYSOFF
#	CA_FREE_TIMES
#	CA_APPOINTMENTS
#	CA_APPOINTMENTS_ARCHIVE
#
    $dbTable_StartDate	= "START_DATE";
    $dbTable_EndDate	= "END_DATE";
    $dbTable_StartTime	= "START_TIME";
    $dbTable_EndTime 	= "END_TIME";


#
# Structure for the CA_APPOINTMENTS table :
#
   $tableAppointmentsOperative = "CA_APPOINTMENTS";
   $tableAppointmentsArchive = "CA_APPOINTMENTS_ARCHIVE";
   //$tableAppointmentsArchive = "CA_APPOINTMENTS";//zaglushka

	$tableAppointments = "CA_APPOINTMENTS";
    $appointmentsF_AppId = "APP_ID";
    $appointmentsF_ClietnId = "CLIENT_ID";
    $appointmentsF_AgendaId = "AGENDA_ID";
//    $dbTable_StartDate ="START_DATE";				//	DATE	//REMARK: Don't delete
//    $dbTable_EndDate ="END_DATE";					//REMARK: Don't delete
//    $dbTable_StartTime ="START_TIME";				//REMARK: Don't delete
//    $dbTable_EndTime ="END_TIME";					//REMARK: Don't delete
    $appointmentsF_AppTypeId = "APPTYPE_ID";
    $appointmentsF_StatusId = "STATUS";
    $appointmentsF_Comment = "COMMENTS";
    $appointmentsF_CreateDate = "CREATE_DATE";
    $appointmentsF_CreateTime = "CREATE_TIME";
    $appointmentsF_MaxNumberClient ="MAX_NUMBER_CLIENT";
    $appointmentsF_IsShared ="IS_SHARED";
    $appointmentsF_Creater ="APP_CREATER";

//  $daysOffF_PatternId  = "PATT_ID";
//  $field_org_code = "ORG_CODE";


#
# Structure for the CA_DELETED_APPOINTMENTS table :
# This table also collects CA_DAYSOFF deleted items.
#
    $tableDeletedAppointments		= "CA_DELETED_APPOINTMENTS";
    $deletedAppointmentsF_AppId		= "APP_ID";
    $deletedAppointmentsF_AgendaId	= "AGENDA_ID";
    $deletedAppointmentsF_Marker	= "MARKER";


#
# Structure for the CA_FREE_TIMES table :
# Table names are equal to CA_DAYSOFF table.
#
	$tableFreeTimes = "CA_FREE_TIMES";
#
# Structure for the CA_DAYSOFF table :
#
	$tableDaysOff = "CA_DAYSOFF";
    $daysOffF_Id = "DAY_ID";
    $daysOffF_SyncId = "SYNC_ID";
    $daysOffF_AgendaId = "AGENDA_ID";
//    $dbTable_StartDate = "START_DATE";			//TODO: Delete from DB
//    $dbTable_EndDate = "END_DATE";				//TODO: Delete from DB
//    $dbTable_StartTime = "START_TIME";	//	BLOCKED_TIME	//REMARK: Don't delete
//    $dbTable_EndTime  = "END_TIME";	//	BLOCKED_TIME_END	//REMARK: Don't delete
    $daysOffF_Comment = "COMMENT";
    $daysOffF_PatternId  = "PATT_ID";
    $daysOffF_Marker  = "MARKER";
   // $field_org_code = "ORG_CODE";

#
# Structure for the CA_DAYSOFF_PATTERN table :
#
	$tableDaysoffPattern = "CA_DAYSOFF_PATTERN";
    $daysoffPatternF_Id = "ID";
//	$daysoffPatternF_DaysoffId = "DAYSOFF_ID";
    $daysoffPatternF_Cycle = "CYCLE";
    $daysoffPatternF_Period = "PERIOD";
    $daysoffPatternF_WeekDays = "WEEK_DAYS";
   // $field_org_code = "ORG_CODE";

#
# Structure for the CA_AGENDAS table :
#
	$tableAgendas = "CA_AGENDAS";
    $agendasF_Id ="AGENDA_ID";
    $agendasF_Name = "FIRSTNAME";
    $agendasF_Password = "PASSWORD";
    $agendasF_Username = "USERNAME";
    $agendasF_Duration = "DURATION";
    $agendasF_StartTime = "START_TIME";
    $agendasF_EndTime = "END_TIME";
    $agendasF_MsoSync = "MSO_SYNC";
    $agendasF_Infix = "INFIX";
    $agendasF_Surname = "SURNAME";
    $agendasF_Number = "AGENDA_NUM";
    $agendasF_IsBlocked = "BLOCKED_THER";
    $agendasF_UserLevel = "USER_LEVEL";
    $agendasF_Login = "LOGIN";
    $agendasF_Gender = "GENDER";
//    $agendasF_CacheStatus ="CACHE_STATUS";					//TODO: Remove from table
    $agendasF_DealerOrgId ="DEALER_ORG_ID";
    $agendasF_OvPeriod = "OV_PERIOD";
    $agendasF_OvFilter = "OV_FILTER";
    $agendasF_OvType = "OV_TYPE";
    $agendasF_MobPhone = "MOB_PHONE";
	$agendasF_TypeOfNotification = "TYPE_OF_NOTIFICATION";
	$agendasF_AccsessAsAsmin = "AGENDA_ACCSESS_AS_ADMIN";
	$agendasF_IsCaosSync = "IS_CAOS_SYNC";
	$agendasF_IniWidgetParams = "INI_WIDGET_PARAMS";
    //	$field_org_code = "ORG_CODE";
    //	$field_white_label_id="WHITE_LABEL_ID";

#
# Structure for the CA_AGENDAS_SYSTEM table :
#
//	$tableAgendasSystem = "CA_AGENDAS_SYSTEM";				//TODO: Remove From DB
//	$agendasSystemF_Id = "ID";
//	$agendasSystemF_AgendaId = "AGENDA_ID";
//	$agendasSystemF_CacheStatus = "CACHE_STATUS";
//	$agendasSystemF_CacheUpdatedDate = "CACHE_UPDATED_DATE";
    //  $field_org_code = "ORG_CODE";
#
# Structure for the CA_CLIENTS_LOGIN table :
#
	$tableClientsLogin = "CA_CLIENTS_LOGIN";
    $clientsLoginF_Id = "CLIENT_ID";
    $clientsLoginF_Password = "PASSWORD";
    $clientsLoginF_Login = "LOGIN";


#
# Structure for the CA_CLIENTS table :
#
	$tableClients = "CA_CLIENTS";
    $clientsF_Id_Row = "ID";
    $clientsF_Id = "CLIENT_ID";
    $clientsF_Name = "FIRSTNAME";
    $clientsF_Gender = "GENDER";
    $clientsF_BirthDate = "BIRTH_DATE";
    $clientsF_Address = "ADDRESS";
    $clientsF_StreetNum = "STREET_NUMBER";
    $clientsF_ZipCode = "ZIP_CODE";
    $clientsF_City  = "CITY";
    $clientsF_Phone = "PHONE";
    $clientsF_Username = "USERNAME";
    $clientsF_Password = "PASSWORD";
    $clientsF_IsBlocked = "BLOCKED";
    $clientsF_IsDisabled = "DISABLED";
    $clientsF_Infix = "INFIX";
    $clientsF_Surname = "SURNAME";
    $clientsF_Number = "CLIENT_NUM";
    $clientsF_Login = "LOGIN";
    $clientsF_MobPhone = "MOB_PHONE";
    $clientsF_TypeOfReminder = "TYPE_OF_REMINDER";
    $clientsF_Initials = "INITIALS";
    $clientsF_Self1 = "SELF1";
    $clientsF_Self2 = "SELF2";
    $clientsF_Self3 = "SELF3";
    $clientsF_Self4 = "SELF4";

    $clientsF_Address2 = "ADDRESS2";
    $clientsF_County = "COUNTY";
    $clientsF_Country = "COUNTRY";

 //   $field_org_code = "ORG_CODE";

#
# Structure for the CA_ORGANIZATION table :
#
	$tableOrganisation = "CA_ORGANIZATIONS";
	$organizationF_OrgCode = "ORG_CODE";
	$organizationF_OrgName = "ORG_NAME";
	$organizationF_OrgDesck = "ORG_DESCK";
	$organizationF_OrgEnabled = "ORG_ENABLED";
	$organizationF_OrgId = "ORG_ID";
	$organizationF_OrgUrlPrefix = "ORG_URL_PREFIX";
	$organizationF_OrgAdress = "ORG_ADDRESS";
	$organizationF_OrgAdressNumber = "ORG_ADDRESS_NUMBER";
	$organizationF_OrgAdress2 = "ORG_ADDRESS2";
	$organizationF_OrgZipcode = "ORG_ZIPCODE";
	$organizationF_OrgCity = "ORG_CITY";
	$organizationF_OrgCounty = "ORG_COUNTY";
	$organizationF_OrgPhone = "ORG_PHONE";
	$organizationF_OrgCountry = "ORG_COUNTRY";
	$organizationF_OrgIsTrial = "ORG_IS_TRIAL";
	$organizationF_OrgX = "ORG_X";
	$organizationF_OrgY = "ORG_Y";
	$organizationF_DealerId = "DEAL_ID";
	$organizationF_SegmentId = "SEGMENT_ID";
	$organizationF_IsTemplate = "IS_TEMPLATE";
	$organizationF_Website = "ORG_WEBSITE";
	//$organizationF_CacheTimePeriod = "CACHE_TIME_PERIOD";

#
# Structure for the CA_SETTINGS table :
#
	$tableSettings = "CA_SETTINGS";
    $settingsF_Id = "ID";
    $settingsF_InfoMail = "INFO_MAIL";
    $settingsF_CompanyName = "COMPANY_NAME";
    $settingsF_Logo = "LOGO";
    $settingsF_TextLogo = "TEXT_LOGO";
    $settingsF_LogoSelect = "LOGO_SELECT";
    $settingsF_DefaultLang = "DEF_LANG";
    $settingsF_DailyOverviewStart = "DAILY_OVERVIEW_START";
    $settingsF_DailyOverviewEnd = "DAILY_OVERVIEW_END";
    $settingsF_DailyOverviewDuration = "DAILY_OVERVIEW_DURATION";
    $settingsF_CompanyTime = "COMPANY_TIME";
    $settingsF_IsSendMail = "IS_SEND_MAIL";
    $settingsF_TimeRefresh = "TIME_REFRESH";
    $settingsF_ClientRgTypes = "CLIENT_REGISTRATION_TYPES";
    $settingsF_ClientRgTypesWidget = "CLIENT_REGISTRATION_TYPES_WIDGET";
    $settingsF_DefoultReminderTime = "DEFOULT_REMINDER_TIME";
    $settingsF_IsSendMailAboutClReg = "IS_SEND_MAIL_ABOUT_CL_REG";
    $settingsF_GreedId = "GREED_ID";
    $settingsF_TimeToWidgettAppDelete ="TIME_TO_WIDGET_APP_DELETE";
    $settingsF_Notification_MobPhone = "NOTIFICATION_MOB_PHONE";
    $settingsF_TypeOfNotification = "TYPE_OF_NOTIFICATION";
    $settingsF_CreateReminderWithoutConfirm = "CREATE_REMINDER_WITHOUT_CONFIRM";
    $settingsF_WidgetFields = "WIDGET_FIELDS";
    $settingsF_TimeZone = "TIME_ZONE";
    $settingsF_IsShowWidgetAgendas = "IS_SHOW_WIDGET_AGENDAS";
    $settingsF_GoogleAnalyticCode = "GOOGLE_ANALYTIC_CODE";

 //   $field_org_code = "ORG_CODE";

#
# Structure for the CA_TIME_ZONES table :
#
    $tableTimeZones			= "CA_TIME_ZONES";
    $timeZonesF_Id			= "ID";
    $timeZonesF_Code		= "CODE";
    $timeZonesF_Gmt			= "GMT";
    $timeZonesF_Descriptor	= "DESCRIPTOR";

#
# Structure for the CA_ORG_PERMISSIONS table :
#

	$tableOrgPermissionsTemp = "CA_ORG_PERMISSIONS_TEMP";
	$tableOrgPermissions = "CA_ORG_PERMISSIONS";
	$orgPermissionsF_Id = "PERM_ID";
	$orgPermissionsF_OrgId = "ORG_ID";

//		$orgPermissionsF_Name = "PERM_NAME";
//		$orgPermissionsF_Value = "PERM_VALUE";

	$orgPermissionsF_StartDate = "PERM_START_DATE";
	$orgPermissionsF_EndDate = "PERM_END_DATE";
	$orgPermissionsF_CountClient = "PERM_COUNT_CLIENT";
	$orgPermissionsF_CountAgenda = "PERM_COUNT_AGENDA";
	$orgPermissionsF_MaxLogin = "PERM_COUNT_MAX_LOGIN";
	$orgPermissionsF_UnitTreking = "PERM_UNIT_TRACKING";
	$orgPermissionsF_UnitQuestionnaire = "PERM_UNIT_QUESTIONNAIRE";
	$orgPermissionsF_UnitQuestionnaireUnlim = "PERM_UNIT_QUESTIONNAIRE_UNLIM";

    $orgPermissionsF_UnitReminder = "PERM_UNIT_REMINDER";
	$orgPermissionsF_LicenseType = "PERM_LICENSE_TYPE";
	$orgPermissionsF_LicenseFee = "PERM_LICENSE_FEE";

#
# Structure for the logged_in table :
#
	$tableLoggedIn = "CA_LOGGED_IN";
	$loggedInF_Id = "ID";
	$loggedInF_UId = "USER_ID";
	$loggedInF_LoggedInTime = "LOGGED_IN_TIME";
	$loggedInF_Level = "LEVEL";
	$loggedInF_SessionId ="SESSION_ID";
	$loggedInF_Status = "STATUS";

#
# Structure for the CA_LANGUAGES table :
#
	$tableLanguages = "CA_LANGUAGES";
	$languagesF_Id = "ID";
	$languagesF_LangCode = "LANG_CODE";
	$languagesF_LangName = "LANG_NAME";
	$languagesF_LangResFile = "LANG_RES_FILE";
	$languagesF_LangI18NConst = "LANG_I18N_CONST";
	$languagesF_LangMoneyFormat = "LANG_MONEY_FORMAT";
	$languagesF_IsActive = "IS_ACTIVE";

#
# Structure for the CA_GENERIC_PROMTS table :
#
	$tablePromptsConst = "CA_GENERIC_PROMTS";
	$promptsF_Id = "ID";
	$promptsF_Constant = "CONSTANT";
	$promptsF_Module  = "MODULE";

#
# Structure for the CA_LANG_CASES table :
#
	$tableLangCases = "CA_LANG_CASES";
	$langCasesF_Id = "ID";
	$langCasesF_LangId = "LANG_ID";
	$langCasesF_LangCode = "LANG_CODE";
	$langCasesF_Suffix = "SUFFIX";
	$langCasesF_Name = "NAME";


#
# Structure for the CA_PROMT_DESCRIPTIONS table :
#
	$tablePromptDescr = "CA_PROMT_DESCRIPTIONS";
	$promptDescrF_Id = "ID";
	$promptDescrF_ConstId = "CONST_ID";
	$promptDescrF_LangId = "LANG_ID";
	$promptDescrF_CaseSuffixId = "CASE_SUFFIX_ID";
	$promptDescrF_Sngl = "SINGULAR";
	$promptDescrF_Plrl = "PLURAL";
//	$field_org_code = "ORG_CODE";

#
# Structure for the CA_PAYMENT_SETTINGS table :
#
	$tablePaymentSettings = "CA_PAYMENT_SETTINGS";
	$paymentF_Id = "ID";
	$paymentF_CostCA = "COST_CA";
	$paymentF_CostTreatment = "COST_TREATMENT";
	$paymentF_CostQuest = "COST_QUEST";
	$paymentF_CostCAnoPay = "COST_CA_NO_PAY";
	$paymentF_CostTreatmentNoPay = "COST_TREATMENT_NO_PAY";
	$paymentF_CostQuestNoPay = "COST_QUEST_NO_PAY";
	$paymentF_Tax = "TAX";
	$paymentF_Koificient = "COEFFICIENT";
	$paymentF_CurId = "CUR_ID";
	$paymentF_CountryCode = "COUNTRY_CODE";
#
# Structure for the CA_PAYMENT_TRANZACTIONS table :
#
	$tablePaymentTranzactions = "CA_PAYMENT_TRANSACTIONS";
	$paymentTranzactionsF_Id = "ID";
	$paymentTranzactionsF_BanckAcaunt = "BANK_ACCOUNT";
	$paymentTranzactionsF_PayName = "PAY_NAME";
	$paymentTranzactionsF_PayAddr = "PAY_ADDR";
	$paymentTranzactionsF_Price = "PRICE";

#
# Structure for the CA_OWNER_INFO table :
#
	$tableOwner = "CA_OWNER_INFO";
	$ownerF_Id = "ID";
	$ownerF_Name = "OWN_NAME";
	$ownerF_PostAddr = "OWN_POST_ADDR";

	$ownerF_PostAddrNum = "OWN_ADDRESS_NUM";
	$ownerF_PostAddr2 = "OWN_ADDRESS2";
	$ownerF_Country = "ONW_COUNTRY";
	$ownerF_County = "OWN_COUNTY";

	$ownerF_Mail = "OWN_MAIL";
	$ownerF_FinancialMail = "OWN_FINANCIAL_MAIL";
	$ownerF_ZipCode = "OWN_ZIP";
	$ownerF_City = "OWN_CITY";
	$ownerF_Phone = "OWN_PHONE";
	$ownerF_Fax = "OWN_FAX";
	$ownerF_Code= "OWN_CODE";
	$ownerF_BankName= "OWN_BANCK_NAME";
	$ownerF_BankAcount= "OWN_BANCK_ACOUNT";
	$ownerF_BankAcountName= "OWN_BANCK_ACOUNT_NAME";
	$ownerF_BankIBANcode= "OWN_BANCK_IBAN_CODE";
	$ownerF_BankBICcode= "OWN_BANCK_BIC_CODE";
	$ownerF_VATnumber= "OWN_VAT_NUMBER";
	$ownerF_COCnumber= "OWN_COC_NUMBER";
//	$ownerF_Country= "COUNTRY_CODE";
	$ownerF_Logo= "LOGO";
	$ownerF_Slogan= "SLOGAN";
	$ownerF_GoogleAnalyticCode= "GOOGLE_ANALYTIC_CODE";

#
# Structure for the CA_DEALERS table :
#
	$tableDealers = "CA_DEALERS";
	$dealerF_Id = "DEAL_ID";
	$dealerF_Name = "DEAL_NAME";
	$dealerF_Addr = "DEAL_ADDR";
	$dealerF_Mail = "DEAL_MAIL";
	$dealerF_ZipCode = "DEAL_ZIPCODE";
	$dealerF_City = "DEAL_CITY";
	$dealerF_Phone = "DEAL_PHONE";
	$dealerF_MAIL = "DEAL_MAIL";
    $dealerF_Country = "DEAL_COUNTRY";
	$dealerF_PromoCode= "DEAL_PROMO_CODE";
	$dealerF_Percentage= "DEAL_PERCENTAGE";

	$dealerF_Address2= "DEAL_ADDRESS2";
	$dealerF_County= "DEAL_COUNTY";
	$dealerF_AddressNum= "DEAL_ADDRESS_NUM";


#
# Structure for the CA_DISCOUNTS table :
#
	$tableDiscounts = "CA_DISCOUNTS";
	$discountsF_Id = "ID";
	$discountsF_NumberOfUsers = "NUMBER_OF_USERS";
	$discountsF_Discount = "DISCOUNT";
	$discountsF_Type = "TYPE";

#
# Structure for the CA_COUNTRY table :
#
	$tableCountry = "CA_COUNTRY";
	$countryF_Id = "ID";
	$countryF_Code = "COU_CODE";
	$countryF_Lang = "COU_LANG";
	$countryF_LocalAddrFormat = "COU_LOCALISATIONS_ADDR_FORMAT";
	$countryF_LocalAddrBuildNum = "COU_LOCALISATIONS_ADDR_BUILD_NUM";

#
# Structure for the CA_TEXT_IN_LOGIN table :
#
	$tableTextInLogin = "CA_TEXT_IN_LOGIN";
	$textInLoginF_Id = "ID";
	$textInLoginF_LangId = "LANG_ID";
    $textInLoginF_InLoginText = "TEXT_IN_LOGIN_PAGE";
#
# Structure for the CA_APP_CLIENTS_ASSIGN table :
#
	$tableAppClientAssignOperative = "CA_APP_ASSIGNED_CLIENTS";
	$tableAppClientAssignArchive = "CA_APP_ASSIGNED_CLIENTS_ARCHIVE";
	//$tableAppClientAssignArchive = "CA_APP_ASSIGNED_CLIENTS";//zaglushka


	$tableAppClientAssign = "CA_APP_ASSIGNED_CLIENTS";
	$AppClientAssignF_Id = "ID";
	$AppClientAssignF_AppId = "APP_ID";
	$AppClientAssignF_ClientId = "CLIENT_ID";
	$AppClientAssignF_CreateDate = "CREATE_DATE";
	$AppClientAssignF_CreateTime = "CREATE_TIME";
	//$field_org_code
#
# Structure for the CA_APP_ASSIGNED_AGENDA table :
#
	$tableAppAgendaAssignOperative = "CA_APP_ASSIGNED_AGENDA";
	$tableAppAgendaAssignArchive = "CA_APP_ASSIGNED_AGENDA_ARCHIVE";
	//$tableAppAgendaAssignArchive = "CA_APP_ASSIGNED_AGENDA";//zaglushka

	$tableAppAgendaAssign = "CA_APP_ASSIGNED_AGENDA";
	$AppAgendaAssignF_Id = "ID";
	$AppAgendaAssignF_AppId = "APP_ID";
	$AppAgendaAssignF_AgendaId = "AGENDA_ID";
	$AppAgendaAssignF_CreateDate = "CREATE_DATE";
	$AppAgendaAssignF_CreateTime = "CREATE_TIME";

#
# Structure for the CA_APPOINTMENT_PATTERN table :
#
	$tableAppointmentPattern = "CA_APPOINTMENTS_PATTERN";
    $AppointmentPatternF_Id = "ID";
	$AppointmentPatternF_AppId = "APP_ID";
    $AppointmentPatternF_Cycle = "CYCLE";
    $AppointmentPatternF_Period = "PERIOD";
    $AppointmentPatternF_WeekDays = "WEEK_DAYS";
   // $field_org_code = "ORG_CODE";

#
# Structure for the CA_MAIL_TEMPLATE table :
#
	$tableMailTemplate = "CA_MAIL_TEMPLATE";
    $MailTemplateF_Id = "ID";
	$MailTemplateF_Recipient = "CREATEDBY";
	$MailTemplateF_Sendto = "SENDTO";
	$MailTemplateF_Subject = "SUBJECT";
    $MailTemplateF_BodyContent = "BODY_CONTENT";
    $MailTemplateF_Identifier = "IDENTIFIER";
    $MailTemplateF_LetterType = "LETTER_TYPE";
    $MailTemplateF_Language = "LANGUAGE";

#
# Structure for the CA_FLEX_FIELDS table :
#
	$tableFlexFields = "CA_FLEX_FIELDS";
	$flexFieldsF_Id = "FLEX_FIELD_ID";
    $flexFieldsF_FieldName = "FIELD_NAME";
    $flexFieldsF_IsShown = "IS_SHOWN";
    $flexFieldsF_IsMandatory = "IS_MANDATORY";
    $flexFieldsF_FormName = "FORM_NAME";
   // $field_org_code = "ORG_CODE";

#
# Structure for the CA_GREEDS table :
#
	$tableGreeds = "CA_GREEDS";
	$greedsF_Id = "GREED_ID";
	$greedsF_Data = "GREED_DATA";

#
# Structure for the CA_AGENDAS_CATEGORIES table :
#
	$tableAgendasCategories = "CA_AGENDAS_CATEGORIES";
	$agCatF_Id = "AGE_CAT_ID";
	$agCatF_Name = "AGE_CAT_NAME";
	// $field_org_code = "ORG_CODE";

#
# Structure for the CA_AGENDAS_ASSIGNED_CATEGORIES table :
#
	$tableAgendasAssignedCategories = "CA_AGENDAS_ASSIGNED_CATEGORIES";
	$agAssignedCatF_Id = "ID";
	//`AGE_CAT_ID`
	//`AGENDA_ID`

#
# Structure for the CA_QW_STEPS table :
#
	$tableQwSteps = "CA_QW_STEPS";
	$qwStepsF_Id = "ID";
	$qwStepsF_StepNum = "STEP_NUMBER";

#
# Structure for the CA_CACHE_FREE_TIME table :							//TODO: Remove From DB
#
//    $tableCache = "CA_CACHE_FREE_TIME";
//    $cacheF_Id = "ID";
//    $cacheF_AgendaId = "AGENDA_ID";
//    $cacheF_Date = "DATE";
//    $cacheF_StartTime = "START_TIME";
//    $cacheF_EndTime = "END_TIME";
//   // $field_org_code = "ORG_CODE";

#
# Structure for the CA_CACHE_LOG table :								//TODO: Remove From DB
#
//        $tableCacheLog = "CA_CACHE_LOG";
//        $cacheLogF_Id = "ID";
//        $cacheLogF_LastUpdtDate = "LAST_UPDATE_DATE";
//        $cacheLogF_TestDate = "TEST_DATE";
//        $cacheLogF_IsTest = "IS_TEST";
//        $cacheLogF_NAttempts = "N_ATTEMPTS";
//        $cacheLogF_ErrorsInfo = "ERRORS_INFO";

#
# Structure for the CA_SMS_PACKAGES table :
#
	$tableSmsPackages = "CA_SMS_PACKAGES";
	$smsPackagesF_Id = "ID";
	$smsPackagesF_SmsPackage = "SMS_PACKAGE";
	$smsPackagesF_PricePerSms = "PRICE_PER_SMS";
#
# Structure for the CA_MONTHLY_PRICE_APP table :
#

	$tableMonthlyPriceApp = "CA_MONTHLY_PRICE_APP";
	$monthlyPriceAppF_Id = "ID";
	$monthlyPriceAppF_Date = "MONTH_DATA";
	$monthlyPriceAppF_AppCount = "APP_COUNT";

#
# Structure for the CA_PAYMENTS_PERIODS table :
#

	$tablePaymentsPeriods = "CA_PAYMENTS_PERIODS";
	$paymentsPeriodsF_Id = "ID";
	$paymentsPeriodsF_StartDate = "PER_START_DATE";
	$paymentsPeriodsF_EndDate = "PER_END_DATE";
	$paymentsPeriodsF_PriceSm = "PRICE_SUM";
	$paymentsPeriodsF_IsPay = "IS_PAY";
	$paymentsPeriodsF_Type = "TYPE";

#
# Structure for the CA_CURRENCY table :
#

	$tableCurrency = "CA_CURRENCY";
	$CurrencyF_Id = "CUR_ID";
	$CurrencyF_HtmlSign = "CUR_HTML_SIGN";
	$CurrencyF_TextSign = "CUR_TEXT_SIGN";
	$CurrencyF_Name = "CUR_NAME";

#
# Structure for the CA_QUESTIONS table :
#
	$tableQuestions = "CA_QUESTIONS";
	$questionsF_Id = "ID";
	$questionsF_Code = "QUESTIONS_CODE";
	$questionsF_Content = "QUESTIONS_CONTENT";

#
# Structure for the CA_ANSWER_TYPES table :
#
	$tableAnswerTypes = "CA_ANSWER_TYPES";
	$answerTypesF_Id = "ID";
	$answerTypesF_TypeName = "TYPE_NAME";

#
# Structure for the CA_ANSWERS table :
#
	$tableAnswers = "CA_ANSWERS";
	$answersF_Id = "ID";
	$answersF_Content = "ANSWERS_CONTENT";
	$answersF_TypeId = "TYPE_ID";
	$answersF_QuestionId = "QUESTION_ID";
	$answersF_IsDefault = "IS_DEFAULT";
	$answersF_OrderIndex = "ORDER_INDEX";
	$answersF_HintText = "HINT_TEXT";
	$answersF_HintTextIsMask = "HINT_TEXT_IS_MASK";
	$answersF_IsRequired = "IS_REQUIRED";

#
# Structure for the CA_QUESTIONAIRES table :
#
	$tableQuestionaires = "CA_QUESTIONAIRES";
	$questionairesF_Id = "ID";
	$questionairesF_Name = "NAME";
	$questionairesF_Type= "TYPE";
	$questionairesF_Style= "STYLE";
	$questionairesF_Comment = "COMMENTS";
	$questionairesF_Top = "TOP_QUESTION";
	$questionairesF_isDisabled = "IS_DISABLED";
	$questionairesF_validCode = "VALID_CODE";

#
# Structure for the CA_QUESTIONAIRES_ASSIGNED_APPOINTMENT_TYPES table :
#

	$tableQuestionairesAssignedAppType = "CA_QUESTIONAIRES_ASSIGNED_APPOINTMENT_TYPES";
	$questionairesAssignedAppTypeF_Id = "ID";
	$questionairesAssignedAppTypeF_QuestionairesId = "QUESTIONAIRES_ID";
	$questionairesAssignedAppTypeF_AppTypeId= "APPOINTMENT_TYPES_ID";


#
# Structure for the CA_QUESTIONAIRES table :
#
	$tableQuestionaireMaps = "CA_QUESTIONAIRE_MAPS";
	$questionairesMapF_Id = "ID";
	$questionairesMapF_QuestionaireId = "QUESTIONAIRE_ID";
	$questionairesMapF_AnswerId = "ANSWER_ID";
	$questionairesMapF_QuestionId = "QUESTION_ID";
	$questionairesMapF_ActionId = "ACTION_ID";

#
# Structure for the CA_QUESTION_HISTORIES table :
#

   $tableQuestionHistoryOperative = "CA_QUESTION_HISTORIES";
   $tableQuestionHistoryArchive = "CA_QUESTION_HISTORIES_ARCHIVE";

	$tableQuestionHistory = "CA_QUESTION_HISTORIES";
	$questionHistoryF_Id = "ID";
	$questionHistoryF_AppId = "APP_ID";
	$questionHistoryF_ClientId = "CLIENT_ID";
	$questionHistoryF_Content = "QUESTIONNAIRE_CONTENT";
	$questionHistoryF_QuestionnaireName = "QUESTIONNAIRE_NAME";

#
# Structure for the CA_QUESTION_FLOW table :
#


	$tableQuestionFlow = "CA_QUESTION_FLOW";
	$questionFlowF_Id = "ID";
	$questionFlowF_QuestionnaireId = "QUESTIONNAIRE_ID";
	$questionFlowF_QuestionId = "QUESTION_ID";
	$questionFlowF_AnswerId = "ANSWER_ID";
	$questionFlowF_AnswerText = "ANSWER_TEXT";

	$questionFlowF_AppId = "APP_ID";
	$questionFlowF_AgendaId = "AGENDA_ID";
	$questionFlowF_AppTypeId = "APP_TYPE_ID";
	$questionFlowF_ClientId = "CLIENT_ID";

	$questionFlowF_CreateTime = "CREATE_TIME";
	$questionFlowF_Identificator = "IDENTIFICATOR";

#
# Structure for the CA_QUESTIONNAIRE_ACTION_TYPES table :
#
	$tableQuestionnaireActTypes = "CA_QUESTIONNAIRE_ACTION_TYPES";
	$questionnaireActTypesF_Id = "ID";
	$questionnaireActTypesF_ActionType = "ACTION_TYPE";

#
# Structure for the CA_QUESTIONNAIRE_FINISH_COMMENTS table :
#
	$tableQuestionnaireComments = "CA_QUESTIONNAIRE_FINISH_COMMENTS";
	$questionnaireCommentsF_Id = "ID";
	$questionnaireCommentsF_Comment = "FINISH_COMMENT";
	$questionnaireCommentsF_MakeAppBtnCaption = "MAKE_APP_BTN_CAPTION";
	$questionnaireCommentsF_AnswerId = "ANSWER_ID";

#
# Structure for the CA_QUESTIONNAIRE_ACTIONS table :
#
	$tableQuestionnaireActions = "CA_QUESTIONNAIRE_ACTIONS";
	$questionnaireActionsF_Id = "ID";
	$questionnaireActionsF_ActionTypeId = "ACTION_TYPE_ID";

	$questionnaireActionsF_CategoryId = "CATEGORY_ID";
	$questionnaireActionsF_CategoryCh = "CATEGORY_CHANGABLE";


	$questionnaireActionsF_AppTypeId = "APP_TYPE_ID";
	$questionnaireActionsF_AppTypeCh = "APP_TYPE_CHANGABLE";

	$questionnaireActionsF_AgendaId = "AGENDA_ID";
	$questionnaireActionsF_AgendaCh = "AGENDA_CHANGABLE";
	$questionnaireActionsF_IsAllAgenda = "IS_ALL_AGENDA";
	$questionnaireActionsF_CommentId = "FINISH_COMMENT_ID";

#
# Structure for the CA_OV_AGENDAS table :
#
    $tableOvAgendas = "CA_OV_AGENDAS";
    $ovAgendas_Id = "ID";
    $ovAgendas_ParentId = "PARENT_ID";
    $ovAgendas_AgendaId = "AGENDA_ID";


#
# Structure for the CA_TICKET table :
#
    $tableTicket = "CA_TICKET";
    $ticketF_Id = "ID";
    $ticketF_Ticket = "TICKET";
    $ticketF_Type = "TYPE";
    $ticketF_UserId = "USER_ID";
    $ticketF_CreateDate = "CREATE_DATE";

#
# Structure for the CA_SEGMENTS table :
#
    $tableSegments = "CA_SEGMENTS";
    $segmentF_Id = "ID";
    $segmentF_Name = "NAME";
    $segmentF_Desc = "DESCRIPTION";

#
# Structure for the CA_MULTYLANGUAGE_VALUES table :
#
    $tableMyltyLanguageValues = "CA_MULTYLANGUAGE_VALUES";
    $multyLanguageValuesF_Id = "ID";
    $multyLanguageValuesF_Language = "LANGUAGE";
    $multyLanguageValuesF_Value = "VALUE";

#
# Structure for the CA_WHITE_LABELS table :
#
    $tableWhiteLabels = "CA_WHITE_LABELS";
    $whiteLabelF_Id = "ID";
    $whiteLabelF_Name = "NAME";
    $whiteLabelF_Domain = "DOMAIN";
    $whiteLabelF_IsSSL = "IS_SSL";
    $whiteLabelF_GoogleKey = "GOOGLE_KEY";
    $whiteLabelF_IsPayment = "IS_PAYMENT";
    $whiteLabelF_IsOrgCreateBO = "IS_ORG_CREATE_BO";
	$whiteLabelF_PaymentSystem = "PAYMENT_SYSTEM";
    $whiteLabelF_DefLang = "DEFAULT_LANGUAGE";
    $whiteLabelF_GoogleAnalytics = "GOOGLE_ANALYTICS";
    $whiteLabelF_WebSiteParam = "WEB_SITE_PARAM";
    $whiteLabelF_TrialPeriod = "TRIAL_PERIOD";
    $whiteLabelF_IsDealer = "IS_DELAER";
    $whiteLabelF_TimeZone = "TIME_ZONE";
    $whiteLabelF_LocalUseInfix = "LOCALISATIONS_USE_INFIX";
    $whiteLabelF_CurId = "CUR_ID";
    $whiteLabelF_IsGCEditebel = "IS_GENERAL_CONDITIONS_EDITEBEL";
    $whiteLabelF_IDEALPartnerId = "IDEAL_PARTNER_ID";
    $whiteLabelF_IsCollectiveSms = "IS_COLLECTIVE_SMS";
    $whiteLabelF_FreeQuestionnaire = "FREE_QUESTIONNAIRE";
    $whiteLabelF_BoekyUse = "BOEKY_USE";
    $whiteLabelF_ExcahngeUse = "EXCHANGE_USE";

#
# Structure for the CA_CAOS_QUEUE table :
#
//    $tableCaoslQueue				= "CA_CAOS_QUEUE";		//TODO: Remove From DB
//    $caosQueueF_Id					= "ID";
//    $caosQueueF_AgendaId			= "AGENDA_ID";
//    $caosQueueF_DateTimeCreation	= "DATE_TIME_CREATION";
//    $caosQueueF_Info				= "INFO";

#
# Structure for the CA_WHITE_LABELS_GENERAL_CONDITIONS table :
#
	$tableWLGeneralCond = "CA_WHITE_LABELS_GENERAL_CONDITIONS";
	$WLGeneralCondF_Id = "ID";
	$WLGeneralCondF_LangId = "LANG_ID";
    $WLGeneralCondF_Text = "TEXT";
#
# Structure for the CA_WHITE_LABELS_GENERAL_CONDITIONS table :
#
	$tableWLLangs = "CA_WHITE_LABELS_LANGS";
	$WLLangsF_Id = "ID";
	$WLLangsF_LangCode = "LANG_CODE";
#
# Structure for the CA_WHITE_LABELS_LDAP_SETTINGS table :
#
	$tableWLLdapSet = "CA_WHITE_LABELS_LDAP_SETTINGS";
	$WLLdapF_Id = "ID";
	$WLLdapF_Use = "LDAP_USE";
	$WLLdapF_Host = "LDAP_HOST";
	$WLLdapF_Login = "LDAP_LOGIN";
	$WLLdapF_Pass = "LDAP_PASS";
	$WLLdapF_Domain = "LDAP_DOMAIN";
#
# Structure for the CA_SEO table :
#
	$tableSeo			= "CA_SEO";
	$seoF_Id			= "ID";
	$seoF_Title			= "TITLE";
	$seoF_KeyWords		= "KEY_WORDS";
	$seoF_Description	= "DESCRIPTION";
	$seoF_LangCode		= "LANG_CODE";
//   $field_org_code	= "ORG_CODE";

#
# Structure for the CA_EXCHANGE table :
#
	$tableExchange			= "CA_EXCHANGE";
	$ExchangeF_AgendaId		= "AGENDA_ID";
	$ExchangeF_User			= "USER";
	$ExchangeF_Password		= "PASSWORD";
	$ExchangeF_SyncState	= "SYNC_STATE";


//
// select app tables operative or archive
//

	if(($_SESSION['app_table_using']=='operativ')||($_SESSION['app_table_using']=='')){
		$tableAppointments = $tableAppointmentsOperative;
    	$tableAppClientAssign = $tableAppClientAssignOperative;
    	$tableAppAgendaAssign = $tableAppAgendaAssignOperative;
    	$tableQuestionHistory = $tableQuestionHistoryOperative;
 	}else{
		$tableAppointments = $tableAppointmentsArchive;
    	$tableAppClientAssign = $tableAppClientAssignArchive;
    	$tableAppAgendaAssign = $tableAppAgendaAssignArchive;
    	$tableQuestionHistory = $tableQuestionHistoryArchive;
	}
?>
