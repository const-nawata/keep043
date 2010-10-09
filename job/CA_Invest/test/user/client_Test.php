<?php
//include("./config.php");
require_once $CA_PATH.'test/user/user_constants.php';
require_once $CA_PATH.'test/session_tuning.php';
require_once $CA_PATH.'test/UT_utils.php';
require_once($CA_PATH."classes/dbl/user_dbl.php");


class client_Test extends PHPUnit_Framework_TestCase{
    // true false

    const _is_all = true;    //  Switch on/off all tests

    const _is_t1 = true;
    const _is_t2 = false;   //   test_deletionOfClients
    const _is_t3 = false;
    const _is_t4 = false;
    const _is_t5 = false;
    const _is_t6 = false;

    const _is_t7 = false;
    const _is_t8 = false;
    const _is_t9 = false;
    const _is_t10 = false;
    const _is_t11 = false;
    const _is_t12 = false;
    const _is_t13 = false;

    const _qnt_clients = 5;  //  Don't change this value

    private $mOrgs = array();
    private $mApps = array();

    protected function setUp(){
        self::deleteEnvironment();
        session_tuning::destroySessionData();
        session_tuning::createSessionData();
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    protected function tearDown(){
         self::deleteEnvironment();
         session_tuning::destroySessionData();
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

//ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!
//This test must be first in the sequence of tests. Set all new tests after this test. It is necessary to excluede cache interferance.
    public function test_addClient_GetClientById_GetClientNameById_GetFullName(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");

        UT_utils::setCronStatusForTest(_IS_BUSY);

        $tst_data = &$this->mOrgs;
        $clients = &$tst_data['clients'];

        if ((self::_is_t1 || self::_is_all) && !$_SESSION['is_skip']){


            $_SESSION['is_skip'] = true;


            self::createEnvironment();

            reset($clients);
            $org_info = each($clients);  //  first step is clients list
            $list = $org_info['value'];



            for ($n_org = 0; $n_org < _QNT_ORGS_UT_TEST; $n_org++){
                $org_info = each($clients);
                $org_clients = &$org_info['value'];

                $_SESSION['org_code'] = $org_info['key'];

                foreach ($org_clients as $key=>$org_cl){
                    $db_client = user_bl::getClientById($org_cl["$clientsF_Id"]);
                    $this->assertEquals($org_cl["$clientsF_Name"], $db_client["$clientsF_Name"], "***** Assert 3. Wrong client's name for organisation with ogr code \"".$_SESSION['org_code']."\". Might be client was not found.*****");

                    $client_e = &$org_clients[$key];
                    $client_name_e = user_bl::GetFullName($client_e["$clientsF_Name"], $client_e["$clientsF_Infix"], $client_e["$clientsF_Surname"]);
                    $client_name = user_bl::GetClientNameById($org_cl["$clientsF_Id"]);
                    $this->assertEquals($client_name_e, $client_name, "***** Assert 3a. Wrong client's full name for organisation with ogr code \"".$_SESSION['org_code']."\". *****");
                }
            }



            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_addClient_and_GetClientById is off!!!');}
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_deletionOfClients(){//     _is_t2
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        $tst_data = &$this->mOrgs;
        $clients = &$tst_data['clients'];

        if ((self::_is_t2 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment(true);
            reset($clients);
            $org_info = each($clients);  //  first step is clients list
            $list = $org_info['value'];

            $org_info = each($clients);  //  second step is the first org code
            $_SESSION['org_code'] = $org_info['key'];
            $org_clients = &$org_info['value'];

            foreach ($org_clients as $key=>$client){
                user_bl::deleteClient($client["$clientsF_Id"]);
                $db_apps = appointments_bl::GetAllAppsForOrg('2009-03-27');
                foreach ($db_apps as $app){
                    foreach ($app['clients'] as $slient_id){
                        $app_type = app_type_bl::getAppTypeById($app["$appointmentsF_AppTypeId"]);
                        $condition = $client["$clientsF_Id"] != $slient_id;
                        $this->assertTrue($condition, "Assert 0. Incorrect deletion of appointment. Appointment with client id = $slient_id was found. App id = ".$app["$appointmentsF_AppId"]." ## Type = ".$app_type["$appTypesF_IsMulty"]);
                    }
                }



            }

            $number_of_clients = self::getClientsQuantityForOrg();
            $this->assertEquals(0, $number_of_clients, "***** Assert 1. Incorrect deletion of all clients from  $tableClients table for sinlge organisation. Organisation still has clients.  Organisation: ".$_SESSION['org_code']."*****");
            $db_apps = appointments_bl::GetAllAppsForOrg('2009-03-27');

            for ($org_num = 1; $org_num < _QNT_ORGS_UT_TEST; $org_num++){
                $org_info = each($clients);
                $_SESSION['org_code'] = $org_info['key'];
                $number_of_clients = self::getClientsQuantityForOrg();
                $this->assertEquals(self::_qnt_clients, $number_of_clients, "***** Assert 2.  Incorrect deletion of all clients from  $tableClients table for sinlge organisation. Clients were aslo deleted from organisation ".$_SESSION['org_code'].".*****");
            }


            foreach ($clients as $org_code=>$org_clients){
                if ($org_code != 'list' && $org_code != _UT_ORG_CODE.'0'){
                    $_SESSION['org_code'] = $org_code;
                    foreach ($list as $client){
                        user_bl::deleteClient($client["$clientsF_Id"]);
                    }
                }
            }

            foreach ($clients as $org_code=>$org_clients){
                if ($org_code != 'list'){
                    $_SESSION['org_code'] = $org_code;
                    $number_of_clients = self::getClientsQuantityForOrg();
                    $this->assertEquals(0, $number_of_clients, "***** Assert 4. Incorrect deletion of all clients from  all organisations. Organisation still has clients. Orgcode: ".$_SESSION['org_code']."*****");
                }
            }


            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_deletionOfClients is off!!!');}
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_getClientsList(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        $tst_data = &$this->mOrgs;
        $clients = &$tst_data['clients'];
        if ((self::_is_t3 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment();
            reset($clients);
            $org_info = each($clients);  //  first step is clients list
            $list = $org_info['value'];

            $_SESSION['org_code'] = _UT_ORG_CODE.'1';
            $new_clients = UT_utils::createClients1_UT(self::_qnt_clients, 'null', 0, false, true, $_SESSION['org_code'].'add', 'Added',  'UT_ClLoginAdd');  //  Client name = org code.add
          foreach ($new_clients as $new_client){
                $clients['list'][] = array("$clientsLoginF_Id"=>$new_client["$clientsLoginF_Id"], "$clientsLoginF_Login"=>$new_client["$clientsLoginF_Login"]);
                $ind = _UT_ORG_CODE.'1';
                $clients["$ind"][] = $new_client;
            }

            $_SESSION['org_code'] = _UT_ORG_CODE.'0';
            $client_list = user_bl::getClientsList(1, 10, $clientsF_Name, '1', '');
            $qnt_clients = count($client_list);
            $this->assertEquals(self::_qnt_clients, $qnt_clients, "***** Assert 5. Wrong quntity of all clients in list.  Orgcode: ".$_SESSION['org_code']."*****");

            $_SESSION['org_code'] = _UT_ORG_CODE.'1';
            $client_list = user_bl::getClientsList(1, 10, $clientsF_Name, '1', '');
            $qnt_clients = count($client_list);
            $this->assertEquals((self::_qnt_clients+self::_qnt_clients), $qnt_clients, "***** Assert 6. Wrong quntity of all clients in list.  Orgcode: ".$_SESSION['org_code']."*****");


            $_SESSION['org_code'] = _UT_ORG_CODE.'2';
            $client_list = user_bl::getClientsList(1, 10, $clientsF_Name, '1', '');
            $qnt_clients = count($client_list);
            $this->assertEquals(self::_qnt_clients, $qnt_clients, "***** Assert 7. Wrong quntity of all clients in list.  Orgcode: ".$_SESSION['org_code']."*****");

            $_SESSION['org_code'] = _UT_ORG_CODE.'1';
            $client_list = user_bl::getClientsList(1, 7, $clientsF_Name, '1', '');
            $qnt_clients = count($client_list);
            $this->assertEquals(7, $qnt_clients, "***** Assert 8. Wrong quntity clients in first page.  Orgcode: ".$_SESSION['org_code']."*****");

            $_SESSION['org_code'] = _UT_ORG_CODE.'1';
            $client_list = user_bl::getClientsList(2, 7, $clientsF_Name, '1', '');
            $qnt_clients = count($client_list);
            $this->assertEquals(3, $qnt_clients, "***** Assert 9. Wrong quntity clients in first page.  Orgcode: ".$_SESSION['org_code']."*****");

            $_SESSION['org_code'] = _UT_ORG_CODE.'1';
            $client_list = user_bl::getClientsList(1, 7, $clientsF_Name, '1', 'add');
            $qnt_clients = count($client_list);
            $this->assertEquals(5, $qnt_clients, "***** Assert 10. Wrong quntity clients if search value was set.  Orgcode: ".$_SESSION['org_code']."*****");

            $client_list = user_bl::getClientsList(1, 7, $clientsF_Surname, '1', 'add');
            $n = 0;
            foreach ($client_list as $client){
                $this->assertEquals('Added'.$n, $client["$clientsF_Surname"], "***** Assert 11. Wrong sorting of clients if ascended order was set.  Orgcode: ".$_SESSION['org_code']."*****");
                $n++;
            }

            $client_list = user_bl::getClientsList(1, 7, $clientsF_Surname, '2', 'add');
            $n = self::_qnt_clients;
            foreach ($client_list as $client){
                $n--;
                $this->assertEquals('Added'.$n, $client["$clientsF_Surname"], "***** Assert 12. Wrong sorting of clients if descended order was set.  Orgcode: ".$_SESSION['org_code']."*****");
            }


            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_getClientsList is off!!!');}
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_doBlockedClient_and_getClientsListActive(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        $tst_data = &$this->mOrgs;
        $clients = &$tst_data['clients'];
        if ((self::_is_t4 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment();
            reset($clients);
            $org_info = each($clients);  //  first step is clients list
            $list = $org_info['value'];

            $_SESSION['org_code'] = _UT_ORG_CODE.'1';
            user_bl::doBlockedClient($list[2]["$clientsF_Id"], 1);


            $_SESSION['org_code'] = _UT_ORG_CODE.'0';
            $client_list = user_bl::getClientsListActive();
            $qnt_clients = count($client_list);
            $this->assertEquals(self::_qnt_clients, $qnt_clients, "***** Assert 13. Wrong quntity clients in organisation ".$_SESSION['org_code']." after client was blocked in organisation "._UT_ORG_CODE."1. *****");

            $_SESSION['org_code'] = _UT_ORG_CODE.'1';
            $client_list = user_bl::getClientsListActive();
            $qnt_clients = count($client_list);
            $this->assertEquals((self::_qnt_clients - 1), $qnt_clients, "***** Assert 14. Wrong quntity clients in organisation ".$_SESSION['org_code']." after client was blocked in organisation "._UT_ORG_CODE."1. *****");

            $_SESSION['org_code'] = _UT_ORG_CODE.'2';
            $client_list = user_bl::getClientsListActive();
            $qnt_clients = count($client_list);
            $this->assertEquals(self::_qnt_clients, $qnt_clients, "***** Assert 15. Wrong quntity clients in organisation ".$_SESSION['org_code']." after client was blocked in organisation "._UT_ORG_CODE."1. *****");

            $_SESSION['org_code'] = _UT_ORG_CODE.'1';
            $client_list = user_bl::getClientsList(1, 10, $clientsF_Name, '1', '');
            foreach ($client_list as $client){
                if ($client["$clientsF_IsDisabled"] == 1) break;
            }
            $this->assertEquals($list[2]["$clientsF_Id"], $client["$clientsF_Id"], "***** Assert 16. Wrong client was blocked in organisation ".$_SESSION['org_code'].". *****");

            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_doBlockedAgenda_and_getClientsListActive off!!!');}
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_getClientsListCount(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        $tst_data = &$this->mOrgs;
        $clients = &$tst_data['clients'];
        if ((self::_is_t5 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment(true);
            reset($clients);
            $org_info = each($clients);  //  first step is clients list
            $list = $org_info['value'];

            $_SESSION['org_code'] = _UT_ORG_CODE.'0';

            $_SESSION['org_code'] = _UT_ORG_CODE.'1';
            $count = user_bl::getClientsListCount('');
            $this->assertEquals(self::_qnt_clients, $count, "***** Assert 17. Wrong quantity of clients was found ".$_SESSION['org_code'].". *****");

            $_SESSION['org_code'] = _UT_ORG_CODE.'0';
            $count = user_bl::getClientsListCount('');
            $this->assertEquals(self::_qnt_clients * 2, $count, "***** Assert 18. Wrong quantity of clients was found ".$_SESSION['org_code'].". *****");

            $_SESSION['org_code'] = _UT_ORG_CODE.'0';
            $count = user_bl::getClientsListCount('Added');
            $this->assertEquals(self::_qnt_clients, $count, "***** Assert 19. Wrong quantity of clients was found ".$_SESSION['org_code'].". *****");

            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_getClientsListCount is off!!!');}
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_GetClientListNotAttachedToApp(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        $tst_data = &$this->mOrgs;
        $clients = &$tst_data['clients'];
        $apps = &$this->mApps;
        if ((self::_is_t6 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment(true);
            reset($clients);
            $org_info = each($clients);  //  first step is clients list
            $list = $org_info['value'];

            $_SESSION['org_code'] = _UT_ORG_CODE.'0';

            $clients_not_attch = user_bl::GetClientListNotAttachedToApp($apps[1]["$appointmentsF_AppId"], '');
            foreach ($clients_not_attch as $na_client){
                $n_intries = substr_count($na_client['value'], 'Added');
                $this->assertEquals(0, $n_intries, "***** Assert 20. Attached client was found in  list.. *****");
            }

            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_GetClientListNotAttachedToApp is off!!!');}
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_GetClientListAttachedToApp_and_GetClientListByIdList(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        $tst_data = &$this->mOrgs;
        $clients = &$tst_data['clients'];
        if ((self::_is_t7 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment(true);
            reset($clients);
            $org_info = each($clients);  //  first step is clients list
            $list = $org_info['value'];

            $_SESSION['org_code'] = _UT_ORG_CODE.'0';

            $ids = "";
            foreach ($list as $client){
                $ids .= $client["$clientsF_Id"].",";
            }

           $length = strlen($ids) - 1;
           $ids = substr($ids, 0, $length);

           $att_clients = user_bl::GetClientListAttachedToApp($ids);
           $n_clients = count($att_clients);
           $this->assertEquals(self::_qnt_clients * 2, $n_clients, "***** Assert 21. Number of Attached clients is wrong. Org Code: ".$_SESSION['org_code']."*****");
           $clients_list = user_bl::GetClientListByIdList($ids);
           $n_clients = count($clients_list);
           $this->assertEquals(self::_qnt_clients * 2, $n_clients, "***** Assert 22. Number of Attached clients is wrong. Org Code: ".$_SESSION['org_code']."*****");

            $_SESSION['org_code'] = _UT_ORG_CODE.'1';
           $att_clients = user_bl::GetClientListAttachedToApp($ids);
           $n_clients = count($att_clients);
           $this->assertEquals(self::_qnt_clients, $n_clients, "***** Assert 23. Number of Attached clients is wrong. Org Code: ".$_SESSION['org_code']."*****");
           $clients_list = user_bl::GetClientListByIdList($ids);
           $n_clients = count($clients_list);
           $this->assertEquals(self::_qnt_clients, $n_clients, "***** Assert 24. Number of Attached clients is wrong. Org Code: ".$_SESSION['org_code']."*****");

            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_GetClientListAttachedToApp is off!!!');}
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_GetClientsListName(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        $tst_data = &$this->mOrgs;
        $clients = &$tst_data['clients'];
        if ((self::_is_t8 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment(true);
            reset($clients);
            $org_info = each($clients);  //  first step is clients list
            $list = $org_info['value'];

            $_SESSION['org_code'] = _UT_ORG_CODE.'0';
            $clients_list = user_bl::GetClientsListName();
            $n_clients = count($clients_list);
            $this->assertEquals(self::_qnt_clients * 2, $n_clients, "***** Assert 25. Number of clients in list is wrong. Org Code: ".$_SESSION['org_code']."*****");

            $_SESSION['org_code'] = _UT_ORG_CODE.'1';
            $clients_list = user_bl::GetClientsListName();
            $n_clients = count($clients_list);
            $this->assertEquals(self::_qnt_clients, $n_clients, "***** Assert 26. Number of clients in list is wrong. Org Code: ".$_SESSION['org_code']."*****");

            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_GetClientsListName is off!!!');}
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_GetClientListOfApp(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        $tst_data = &$this->mOrgs;
        $clients = &$tst_data['clients'];
        $apps = &$this->mApps;
        if ((self::_is_t9 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;

            self::createEnvironment(true);
            reset($clients);
            $org_info = each($clients);  //  first step is clients list
            $list = $org_info['value'];


                $_SESSION['org_code'] = _UT_ORG_CODE.'0';
                $app = &$apps[1];
                $list_expect = &$app['clients'];
                $qnt_expect = count($list_expect);

                $list_check  = appointments_bl::GetClientListOfApp($app["$appointmentsF_AppId"]);
                $qnt_check = count($list_check) - 6;
                $this->assertEquals($qnt_expect, $qnt_check, "***** Assert 27. Wrong quantity of clients in list if appointment belongs to organization. Org code: ".$_SESSION['org_code']."*****");



                $_SESSION['org_code'] = _UT_ORG_CODE.'1';
                $app = &$apps[1];
                $list_expect = &$app['clients'];
                $qnt_expect = 0;

                $list_check  = appointments_bl::GetClientListOfApp($app["$appointmentsF_AppId"]);
                $qnt_check = count($list_check) - 6;
                $this->assertEquals($qnt_expect, $qnt_check, "***** Assert 27a. Wrong quantity of clients in list if appointment does not belong to organization. Org code: ".$_SESSION['org_code']."*****");


                $_SESSION['org_code'] = _UT_ORG_CODE.'1';
                $app = &$apps[6];
                $list_expect = &$app['clients'];
                $qnt_expect = count($list_expect);

                $list_check  = appointments_bl::GetClientListOfApp($app["$appointmentsF_AppId"]);
                $qnt_check = count($list_check) - 6;
                $this->assertEquals($qnt_expect, $qnt_check, "***** Assert 28. Wrong quantity of clients in list if appointment belongs to organization. Org code: ".$_SESSION['org_code']."*****");


                $_SESSION['org_code'] = _UT_ORG_CODE.'0';
                $app = &$apps[6];
                $list_expect = &$app['clients'];
                $qnt_expect = 0;

                $list_check  = appointments_bl::GetClientListOfApp($app["$appointmentsF_AppId"]);
                $qnt_check = count($list_check) - 6;
                $this->assertEquals($qnt_expect, $qnt_check, "***** Assert 28a. Wrong quantity of clients in list if appointment  does not belong to organization. Org code: ".$_SESSION['org_code']."*****");

            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_GetClientListOfApp is off!!!');}
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_changePassAndLoginClient(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        $tst_data = &$this->mOrgs;
        $clients = &$tst_data['clients'];
        if ((self::_is_t10 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment();
            reset($clients);
            $org_info = each($clients);  //  first step is clients list
            $list = $org_info['value'];



            $_SESSION['org_code'] = _UT_ORG_CODE.'0';
            $ind = _UT_ORG_CODE.'0';
            $client_sel = &$clients["$ind"][2];

            $client_sel['oldpass'] = $client_sel["$clientsF_Password"];
            $client_sel['newpass1'] = '1q1q1q';
            $client_sel['newpass2'] = '1q1q1q';
            $client_sel["$clientsLoginF_Login"] = 'newutlogin';

            $_SESSION['level'] = "user";
            user_bl::changePassAndLoginClient($client_sel, 'password');

            $_SESSION['org_code'] = _UT_ORG_CODE.'1';
            $client_chk = user_bl::getClientById($client_sel["$clientsF_Id"]);

            $this->assertEquals($client_sel["$clientsLoginF_Login"], $client_chk["$clientsLoginF_Login"], "***** Assert 30. Client's login was not changed. *****");
            $this->assertEquals($client_sel["newpass1"], $client_chk["$clientsF_Password"], "***** Assert 31. Client's password was not changed. *****");

            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_changePassAndLoginClient is off!!!');}
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_getAppClientListCount(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        $tst_data = &$this->mOrgs;
        $clients = &$tst_data['clients'];
        if ((self::_is_t11 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment(true);
            reset($clients);
            $org_info = each($clients);  //  first step is clients list
            $list = $org_info['value'];
            $apps = &$this->mApps;

            $_SESSION['level'] = 'therapist';
            $_SESSION['client_id'] = $_SESSION['clients'][0]["$clientsF_Id"];

            $_SESSION['org_code'] = _UT_ORG_CODE.'0';

            $_SESSION['mode_old_or_new'] = "new";
            $n_apps = appointments_bl::getAppClientListCount('');
            $this->assertEquals(2, $n_apps, "***** Assert 32. Wrong quantity of clients appointments. Org code: ".$_SESSION['org_code']." # Mode: ".$_SESSION['mode_old_or_new']." *****");

            $_SESSION['mode_old_or_new'] = "old";
            $n_apps = appointments_bl::getAppClientListCount('');
            $this->assertEquals(4, $n_apps, "***** Assert 33. Wrong quantity of clients appointments. Org code: ".$_SESSION['org_code']." # Mode: ".$_SESSION['mode_old_or_new']." *****");



            $_SESSION['org_code'] = _UT_ORG_CODE.'1';

            $_SESSION['mode_old_or_new'] = "new";
            $n_apps = appointments_bl::getAppClientListCount('');
            $this->assertEquals(2, $n_apps, "***** Assert 34. Wrong quantity of clients appointments. Org code: ".$_SESSION['org_code']." # Mode: ".$_SESSION['mode_old_or_new']." *****");

            $_SESSION['mode_old_or_new'] = "old";
            $n_apps = appointments_bl::getAppClientListCount('');
            $this->assertEquals(4, $n_apps, "***** Assert 35. Wrong quantity of clients appointments. Org code: ".$_SESSION['org_code']." # Mode: ".$_SESSION['mode_old_or_new']." *****");

            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_getAppClientListCount is off!!!');}
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_getAppToConfirmListCount(){  //   _is_t12    ***     Asserts: 36, 37, 38
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        $tst_data = &$this->mOrgs;
        $clients = &$tst_data['clients'];
        if ((self::_is_t12 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment(true);
            reset($clients);
            $org_info = each($clients);  //  first step is clients list
            $list = $org_info['value'];
            $apps = &$this->mApps;

            $_SESSION['org_code'] = _UT_ORG_CODE.'0';

            $_SESSION['mode_old_or_new'] = "old";

            $ag_id = $_SESSION['agendas'][0]["$agendasF_Id"];
            $n_apps = appointments_bl::getAppToConfirmListCount('', $ag_id);
            $this->assertEquals(2, $n_apps, "***** Assert 36. Wrong quantity of appointments for confirm. Org code: ".$_SESSION['org_code']." # Mode: ".$_SESSION['mode_old_or_new']." # Agenda id: $ag_id *****");

            $ag_id = $_SESSION['agendas'][1]["$agendasF_Id"];
            $n_apps = appointments_bl::getAppToConfirmListCount('', $ag_id);
            $this->assertEquals(1, $n_apps, "***** Assert 37. Wrong quantity of appointments for confirm. Org code: ".$_SESSION['org_code']." # Mode: ".$_SESSION['mode_old_or_new']." # Agenda id: $ag_id *****");

            $ag_id = 'all';
            $n_apps = appointments_bl::getAppToConfirmListCount('', 'all');
            $this->assertEquals(3, $n_apps, "***** Assert 38. Wrong quantity of appointments for confirm. Org code: ".$_SESSION['org_code']." # Mode: ".$_SESSION['mode_old_or_new']." # Agenda id: $ag_id *****");

            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_getAppToConfirmListCount is off!!!');}
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_GetClientsOrg(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        $tst_data = &$this->mOrgs;
        $clients = &$tst_data['clients'];



        if ((self::_is_t13 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment();


//utils_bl::printArray($clients, 'clients');

            reset($clients);
            $org_info = each($clients);  //  first step is clients list
            $list = $org_info['value'];

            $_SESSION['org_code'] =  _UT_ORG_CODE.'1';
//echo "org code:".$_SESSION['org_code']."<br>";
            foreach ($list as &$client_l){
                $org_list = user_bl::GetClientsOrg($client_l["$clientsF_Id"]);
                foreach ($org_list as $item){
                    $condition = $item['org_code'] != $_SESSION['org_code'];
                    $this->assertTrue($condition, "Assert 39. Not needed data in list.");

                }
                $n_items = count($org_list);
                $this->assertEquals(_QNT_ORGS_UT_TEST - 1, $n_items, "***** Assert 40. Wrong quantity items in list. Org code: ".$_SESSION['org_code']." *****");



//utils_bl::printArray($org_list, 'org_list');

            }

//utils_bl::printArray($list, 'list');

//////   asserts




            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_GetClientsOrg is off!!!');}
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

//ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!  ATTENTION!!!
//This test must be last in the sequence of tests. Set all new tests before this test.
    public function test_LAST_MANDATORY_FICTIVE_TEST(){        //  Last test. Mandatory. Don't put any test after this one.
        UT_utils::setCronStatusForTest(_IS_NOT_BUSY);
        unset($_SESSION['is_skip']);
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------

    private function getClientsQuantityForOrg(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
            $sql_string = "select count(*) as count from $tableClients where $field_org_code='".$_SESSION['org_code']."'";

//echo "<br>$sql_string<br>";

            $info = utils_bl::executeMySqlSelectQuery($sql_string);
            return $info[0]['count'];
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    private function createEnvironment($isCreateApps=false,  $n_clients = 0){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        global $APPROVE; $APPROVE = 0;
        $tst_data = &$this->mOrgs;


        (!$n_clients) ? $qnt_clients = self::_qnt_clients : $qnt_clients = $n_clients;

        $tst_data['clients']['list'] = 'null';
        $list = &$tst_data['clients']['list'];
        $tst_data['orgs'] = array();
        $orgs = &$tst_data['orgs'];
        for ($n_org = 0; $n_org < _QNT_ORGS_UT_TEST; $n_org++){
            $_SESSION['org_code'] = _UT_ORG_CODE.$n_org;
            $orgs[] = UT_utils::addOrg_UT($n_org);

            $clients = UT_utils::createClients1_UT($qnt_clients, $list, 0, false, true, $_SESSION['org_code']);  //  Client name = org code

            if ($n_org == 0){
                $list = array();
                foreach ($clients as $client){
                    $list[] = array("$clientsLoginF_Id"=>$client["$clientsLoginF_Id"], "$clientsLoginF_Login"=>$client["$clientsLoginF_Login"]);
                }
            }
            $tst_data['clients'][$_SESSION['org_code']] = $clients;
        }
        $clients = &$tst_data['clients'];

        if ($isCreateApps){
            $_SESSION['org_code'] = _UT_ORG_CODE.'0';

            $new_clients = UT_utils::createClients1_UT(self::_qnt_clients, 'null', 0, false, true, $_SESSION['org_code'].'add', 'Added',  'UT_ClLoginAdd');  //  Client name = org code.add
            foreach ($new_clients as $new_client){
                $clients['list'][] = array("$clientsLoginF_Id"=>$new_client["$clientsLoginF_Id"], "$clientsLoginF_Login"=>$new_client["$clientsLoginF_Login"]);
                $ind = _UT_ORG_CODE.'0';
                $clients["$ind"][] = $new_client;
            }

            $ind = _UT_ORG_CODE.'0';
            $_SESSION['clients'] = $tst_data['clients']["$ind"];

            $_SESSION['agendas'] = UT_utils::createAgendas_UT(3);
            $_SESSION['app_types']  = UT_utils::createAppTypes_UT();
            $multi_app_types = UT_utils::createAppTypes_UT(1);
            $app_types = &$_SESSION['app_types'];
            foreach ($multi_app_types as $app_type){
                $app_types[] = $app_type;
            }

            $app = array(
            "$appointmentsF_AppId"=>'null',
            "clients"=>array(0=>$_SESSION['clients'][0]["$clientsF_Id"]),
            "agendas"=>array(0=>$_SESSION['agendas'][0]["$agendasF_Id"]),
            "$appointmentsF_ClietnId"=>0,
            "$appointmentsF_Date"=>'2009-03-27',
            "$appointmentsF_SartTime"=>'10:00',
            "$appointmentsF_AppTypeId"=>$app_types[0]["$appTypesF_Id"],
            "$appointmentsF_AgendaId"=>$_SESSION['agendas'][0]["$agendasF_Id"],

            "$appointmentsF_StatusId"=>3,
            "$appointmentsF_Comment"=>'Unit test for clients functions. Which tests were created by C.Kolenchenko (ckolenchenko@yukon.cv.ua)',
            "$appointmentsF_EndDate"=>'2009-03-27',
            "$appointmentsF_MaxNumberClient"=>1,
            "$appointmentsF_IsShared"=>0,
            "$appointmentsF_CreateDate"=>'2009-03-27',
            "$appointmentsF_Creater"=>1
            );

            $app_ind = 0;
            $apps = array();
            $apps = UT_utils::addAppointment_UT($app, $apps);
            appointments_dbl::AssignClientsToApp($app['clients'], $apps[$app_ind]["$appointmentsF_AppId"]); $app_ind++;

            $app['clients'][0] = $_SESSION['clients'][5]["$clientsF_Id"];
            $app['clients'][]   = $_SESSION['clients'][6]["$clientsF_Id"];
            $app['clients'][]   = $_SESSION['clients'][7]["$clientsF_Id"];
            $app['clients'][]   = $_SESSION['clients'][8]["$clientsF_Id"];
            $app['clients'][]   = $_SESSION['clients'][9]["$clientsF_Id"];


            $app["$appointmentsF_AppId"] = 'null';
            $app["$appointmentsF_AppTypeId"] = $app_types[13]["$appTypesF_Id"];
            $app["$appointmentsF_SartTime"] = '14:00';
            $apps = UT_utils::addAppointment_UT($app, $apps);
            appointments_dbl::AssignClientsToApp($app['clients'], $apps[$app_ind]["$appointmentsF_AppId"]); $app_ind++;


            $date_future = date("Y-m-d");
            $date_future = utils_bl::AddDaysToDbDate($date_future, 1);

//echo "date_future: $date_future<br>";


             //  Status new.
            $app["$appointmentsF_AppId"] = 'null';
            $app["$appointmentsF_StatusId"] = 1;
            $app["$appointmentsF_AppTypeId"] = $app_types[0]["$appTypesF_Id"];
            $app["$appointmentsF_SartTime"] = '11:00';
            $app["$appointmentsF_Date"] = $app["$appointmentsF_EndDate"] = '2009-03-27';
            $app["clients"] = array(0=>$_SESSION['clients'][0]["$clientsF_Id"]);
            $apps = UT_utils::addAppointment_UT($app, $apps);
            appointments_dbl::AssignClientsToApp($app['clients'], $apps[$app_ind]["$appointmentsF_AppId"]);$app_ind++;

            $app["$appointmentsF_AppId"] = 'null';
            $app["$appointmentsF_SartTime"] = '12:00';
            $app["$appointmentsF_Date"] = $app["$appointmentsF_EndDate"] = $date_future;
            $apps = UT_utils::addAppointment_UT($app, $apps);
            appointments_dbl::AssignClientsToApp($app['clients'], $apps[$app_ind]["$appointmentsF_AppId"]); $app_ind++;

            $app["$appointmentsF_AppId"] = 'null';
            $app["agendas"][0] = $_SESSION['agendas'][1]["$agendasF_Id"];
            $app["$appointmentsF_Date"] = $app["$appointmentsF_EndDate"] = $date_future;
            $app["$appointmentsF_SartTime"] = '13:00';
            $apps = UT_utils::addAppointment_UT($app, $apps);
            appointments_dbl::AssignClientsToApp($app['clients'], $apps[$app_ind]["$appointmentsF_AppId"]);$app_ind++;

/*            $app["$appointmentsF_AppId"] = 'null';
            $app["$appointmentsF_AgendaId"] = $_SESSION['agendas'][1]["$agendasF_Id"];
            $app["$appointmentsF_Date"] = $app["$appointmentsF_EndDate"] = $date_future;
            $app["$appointmentsF_SartTime"] = '12:00';
            $apps = UT_utils::addAppointment_UT($app, $apps);
            appointments_dbl::AssignClientsToApp($app['clients'], $apps[$app_ind]["$appointmentsF_AppId"]);$app_ind++;*/






//  Appointments for another organization. Status new.
            $_SESSION['org_code'] = _UT_ORG_CODE.'1';
            $new_agendas = UT_utils::createAgendas_UT(3);
            foreach ($new_agendas as $agnd){
                $_SESSION['agendas'][] = $agnd;
            }
            $new_app_types  = UT_utils::createAppTypes_UT();
            foreach ($new_app_types as $app_type){
                $app_types[] = $app_type;
            }
            $app["$appointmentsF_AppTypeId"] = $app_types[25]["$appTypesF_Id"];


//utils_bl::printArray($app_types, 'app_types');

            $app["$appointmentsF_AgendaId"] = $_SESSION['agendas'][3]["$agendasF_Id"];

            $app["$appointmentsF_AppId"] = 'null';
            $app["$appointmentsF_SartTime"] = '11:00';
            $app["$appointmentsF_Date"] = $app["$appointmentsF_EndDate"] = $date_future;
            $apps = UT_utils::addAppointment_UT($app, $apps);
            appointments_dbl::AssignClientsToApp($app['clients'], $apps[$app_ind]["$appointmentsF_AppId"]);$app_ind++;

            $app["$appointmentsF_AppId"] = 'null';
            $app["$appointmentsF_SartTime"] = '12:00';
            $app["$appointmentsF_Date"] = $app["$appointmentsF_EndDate"] = $date_future;
            $apps = UT_utils::addAppointment_UT($app, $apps);
            appointments_dbl::AssignClientsToApp($app['clients'], $apps[$app_ind]["$appointmentsF_AppId"]);$app_ind++;

            $app["$appointmentsF_AppId"] = 'null';
            $app["$appointmentsF_SartTime"] = '13:00';
            $app["$appointmentsF_Date"] = $app["$appointmentsF_EndDate"] = '2009-03-27';
            $apps = UT_utils::addAppointment_UT($app, $apps);
            appointments_dbl::AssignClientsToApp($app['clients'], $apps[$app_ind]["$appointmentsF_AppId"]);$app_ind++;

            $app["agendas"][0] = $_SESSION['agendas'][4]["$agendasF_Id"];
            $app["$appointmentsF_SartTime"] = '15:00';
            $apps = UT_utils::addAppointment_UT($app, $apps);
            appointments_dbl::AssignClientsToApp($app['clients'], $apps[$app_ind]["$appointmentsF_AppId"]);$app_ind++;

            $this->mApps = $apps;


//utils_bl::printArray($this->mApps, 'mApps');
        }
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------

    private function deleteEnvironment(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        $tst_data = &$this->mOrgs;
        $orgs = &$tst_data['orgs'];
        $clients = &$tst_data['clients'];


        if (isset($clients))
            foreach ($clients as $org_code=>$org_clients){
                $_SESSION['org_code'] = $org_code;
                foreach ($org_clients as $client){
                    user_bl::deleteClient($client["$clientsF_Id"]);
                }
            }

        $_SESSION['org_code'] = _UT_ORG_CODE.'0';

        UT_utils::deleteAllOrgDataFromTable($tableAppAgendaAssign);
        UT_utils::deleteAllOrgDataFromTable($tableAppClientAssign);
        UT_utils::deleteAllOrgDataFromTable($tableAppointments);
        UT_utils::deleteAllOrgDataFromTable($tableAgendas);
        UT_utils::deleteAllOrgDataFromTable($tableAppTypes);


        if (isset($orgs))
            foreach ($orgs as $org){
                $id = $org['perm']["$orgPermissionsF_Id"];
                $sql_string = "delete from $tableOrgPermissions where $orgPermissionsF_Id = $id";
                $rez=mysql_query($sql_string);
            }

        UT_utils::deleteAllOrgDataFromTable($tableOrganisation);
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------
}



//  TEMPLATE TEMPLATE TEMPLATE TEMPLATE TEMPLATE TEMPLATE TEMPLATE TEMPLATE TEMPLATE TEMPLATE
/*
//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function test_Template(){
        global $CA_PATH;include($CA_PATH."variables_DB.php");
        $tst_data = &$this->mOrgs;
        $clients = &$tst_data['clients'];
        if ((self::_is_t13 || self::_is_all) && !$_SESSION['is_skip']){
            $_SESSION['is_skip'] = true;
            self::createEnvironment();
            reset($clients);
            $org_info = each($clients);  //  first step is clients list
            $list = $org_info['value'];



//////   asserts




            $_SESSION['is_skip'] = false;
        }else{$this->markTestSkipped('test_Model is off!!!');}
    }
*/




//utils_bl::printArray($clients, 'clients');
?>
