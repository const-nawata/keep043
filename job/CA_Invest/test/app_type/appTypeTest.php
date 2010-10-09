<?php
require_once 'PHPUnit/Framework/TestCase.php';

require_once("./classes/pl/app_type_pl.php");


require_once("./common_agenda_fns.php");


class appTypeTest extends PHPUnit_Framework_TestCase
{
 public $app_type;
 public $app_type_edit;
 public $id;
 public $session_temp;
	
    protected function setUp()
    {include("variables_DB.php");
    
      $this->session_temp=$_SESSION['org_code'];
       $_SESSION['org_code']='root';
       $this->app_type["$appTypesF_Name"]= 'test3';
       $this->app_type["$appTypesF_Time"]= 30;
       $this->app_type["$appTypesF_Color"]= '#eeeeee';   
       $this->app_type["$appTypesF_MinTime"]= 1 ;   
       $this->app_type["$appTypesF_MaxTime"]=7;           
       $this->app_type["$appTypesF_NumberApp"]= 13;
       $this->app_type["$appTypesF_Tariff"]= 130.000;
       $this->app_type["$appTypesF_IsPublic"]= 1 ;
       $this->app_type["$appTypesF_IsMulty"]= 1 ;
	   $this->app_type["$appTypesF_AppComment"]= 'artur test' ; 
       
	   $this->app_type_edit["$appTypesF_Name"]= 'test3_edit';
       $this->app_type_edit["$appTypesF_Time"]= 40;
       $this->app_type_edit["$appTypesF_Color"]= '#e2e2e2';   
       $this->app_type_edit["$appTypesF_MinTime"]= 4 ;   
       $this->app_type_edit["$appTypesF_MaxTime"]= 50;           
       $this->app_type_edit["$appTypesF_NumberApp"]= 16;
       $this->app_type_edit["$appTypesF_Tariff"]= 140.000;
       $this->app_type_edit["$appTypesF_IsPublic"]= 0 ;
       $this->app_type_edit["$appTypesF_IsMulty"]= 0 ;
	   $this->app_type_edit["$appTypesF_AppComment"]= 'artur test test' ; 
	   
	   
    }    
//-----------------------------------------------------------------------------------------------------------------
        

 public function testgetAppTypeById()
 {include("variables_DB.php");
  $id=appTypeTest::AddAppType($this->app_type);
    $app_type=app_type_bl::getAppTypeById($id);
      $rezult=appTypeTest::DeleteAppType($id); 
      
        $this->assertEquals($app_type["$appTypesF_Name"], $this->app_type["$appTypesF_Name"] );
        $this->assertEquals($app_type["$appTypesF_Time"],  $this->app_type["$appTypesF_Time"] );
        $this->assertEquals($app_type["$appTypesF_Color"],  $this->app_type["$appTypesF_Color"] );   
        $this->assertEquals($app_type["$appTypesF_MinTime"], $this->app_type["$appTypesF_MinTime"] );   
        $this->assertEquals($app_type["$appTypesF_MaxTime"], $this->app_type["$appTypesF_MaxTime"] );           
        $this->assertEquals($app_type["$appTypesF_NumberApp"],$this->app_type["$appTypesF_NumberApp"] );
        $this->assertEquals($app_type["$appTypesF_Tariff"],   $this->app_type["$appTypesF_Tariff"] );
        $this->assertEquals($app_type["$appTypesF_IsPublic"], $this->app_type["$appTypesF_IsPublic"] );
        $this->assertEquals($app_type["$appTypesF_IsMulty"], $this->app_type["$appTypesF_IsMulty"] );        
		$this->assertEquals($app_type["$appTypesF_AppComment"], $this->app_type["$appTypesF_AppComment"] ); 
          
    }
 
 public function testgetAppTypeListAll()
 {include("variables_DB.php");
 $id1=appTypeTest::AddAppType();
 $id2=appTypeTest::AddAppType();
    $app_type_list=app_type_bl::getAppTypeListAll();
        $this->assertEquals(count($app_type_list), '2' );
   $rezult=appTypeTest::DeleteAppType($id1); 
   $rezult=appTypeTest::DeleteAppType($id2); 
             
 }
 
 public function testgetAppTypeListCount()
 {include("variables_DB.php");
 $id1=appTypeTest::AddAppType($this->app_type);
 $id2=appTypeTest::AddAppType($this->app_type_edit);
    $app_type_count=app_type_bl::getAppTypeListCount('');
    $this->assertEquals($app_type_count, '2' );
    $app_type_count=app_type_bl::getAppTypeListCount('test3_edit');
    $this->assertEquals($app_type_count, '1' );
    
        $rezult=appTypeTest::DeleteAppType($id1); 
        $rezult=appTypeTest::DeleteAppType($id2);          
 }
 
 public function testaddAppType()
 {include("variables_DB.php");
  $this->app_type;
      $this->app_type["$appTypesF_Id"]= 'null';
    //empty data
   $temp=$this->app_type["$appTypesF_Name"];
    $this->app_type["$appTypesF_Name"]= '';
    $rez_valid=app_type_bl::editAppType($this->app_type);
    $this->app_type["$appTypesF_Name"]= $temp;
    $this->assertNotEquals($rez_valid['rezult'], '1' );
    $this->assertEquals($rez_valid['focus'], $appTypesF_Name );
    
    $temp=$this->app_type["$appTypesF_Time"];
    $this->app_type["$appTypesF_Time"]= '';
    $rez_valid=app_type_bl::editAppType($this->app_type);
    $this->app_type["$appTypesF_Time"]= $temp;
    $this->assertNotEquals($rez_valid['rezult'], '1' );
    $this->assertEquals($rez_valid['focus'], $appTypesF_Time );
    
    //not unic datanmda
    $temp=$this->app_type["$appTypesF_Name"];
    $id2=appTypeTest::AddAppType($this->app_type);
    $rez_valid=app_type_bl::editAppType($this->app_type);
    $rezult=appTypeTest::DeleteAppType($id2);
    $this->assertNotEquals($rez_valid['rezult'], '1' );
    $this->assertEquals($rez_valid['focus'], $appTypesF_Name );
    

    //real data 
    $rez_valid=app_type_bl::editAppType($this->app_type);
    $this->id=mysql_insert_id();
    $this->assertEquals($rez_valid['rezult'], '1' );
    $app_type_count=app_type_bl::getAppTypeListCount('');
    $this->assertEquals($app_type_count, '1' );
    
        $app_type=app_type_bl::getAppTypeById($this->id);
    
        $this->assertEquals($app_type["$appTypesF_Name"], $this->app_type["$appTypesF_Name"] );
        $this->assertEquals($app_type["$appTypesF_Time"],  $this->app_type["$appTypesF_Time"] );
        $this->assertEquals($app_type["$appTypesF_Color"],  $this->app_type["$appTypesF_Color"] );   
        $this->assertEquals($app_type["$appTypesF_MinTime"], $this->app_type["$appTypesF_MinTime"] );   
        $this->assertEquals($app_type["$appTypesF_MaxTime"], $this->app_type["$appTypesF_MaxTime"] );           
        $this->assertEquals($app_type["$appTypesF_NumberApp"],$this->app_type["$appTypesF_NumberApp"] );
        $this->assertEquals($app_type["$appTypesF_Tariff"],   $this->app_type["$appTypesF_Tariff"] );
        $this->assertEquals($app_type["$appTypesF_IsPublic"], $this->app_type["$appTypesF_IsPublic"] );
        $this->assertEquals($app_type["$appTypesF_IsMulty"], $this->app_type["$appTypesF_IsMulty"] );        
		$this->assertEquals($app_type["$appTypesF_AppComment"], $this->app_type["$appTypesF_AppComment"] ); 

		$rezult=appTypeTest::DeleteAppType($this->id);
	$_SESSION["test_app_type_id"]=$this->id;
 }

 public function testeditAppType()
 {include("variables_DB.php");
 $id=appTypeTest::AddAppType($this->app_type);
  $this->app_type_edit;
  $this->id=$id;
      $this->app_type_edit["$appTypesF_Id"]= $this->id;
       $this->app_type["$appTypesF_Id"]= $this->id;
    //empty data
    $temp=$this->app_type["$appTypesF_Name"];
    $this->app_type["$appTypesF_Name"]= '';
    $rez_valid=app_type_bl::editAppType($this->app_type);
    $this->app_type["$appTypesF_Name"]= $temp;
    $this->assertNotEquals($rez_valid['rezult'], '1' );
    $this->assertEquals($rez_valid['focus'], $appTypesF_Name );
    
    $temp=$this->app_type["$appTypesF_Time"];
    $this->app_type["$appTypesF_Time"]= '';
    $rez_valid=app_type_bl::editAppType($this->app_type);
    $this->app_type["$appTypesF_Time"]= $temp;
    $this->assertNotEquals($rez_valid['rezult'], '1' );
    $this->assertEquals($rez_valid['focus'], $appTypesF_Time );
    
    //not unic datanmda
            
    $id1=appTypeTest::AddAppType($this->app_type_edit);
    
        
    $temp=$this->app_type["$appTypesF_Name"];
    $this->app_type["$appTypesF_Name"]=$this->app_type_edit["$appTypesF_Name"];
    $this->app_type["$appTypesF_Id"]= $id;

  $rez_valid=app_type_bl::editAppType($this->app_type);
      $this->app_type["$appTypesF_Name"]= $temp;
    $rezult=appTypeTest::DeleteAppType($id1);

    $this->assertNotEquals($rez_valid['rezult'], '1' );
    $this->assertEquals($rez_valid['focus'], $appTypesF_Name );
    

    //real data 
    $rez_valid=app_type_bl::editAppType($this->app_type_edit);
    $this->assertEquals($rez_valid['rezult'], '1' );
    
        $app_type=app_type_bl::getAppTypeById($this->id);
    
        $this->assertEquals($app_type["$appTypesF_Name"], $this->app_type_edit["$appTypesF_Name"] );
        $this->assertEquals($app_type["$appTypesF_Time"],  $this->app_type_edit["$appTypesF_Time"] );
        $this->assertEquals($app_type["$appTypesF_Color"],  $this->app_type_edit["$appTypesF_Color"] );   
        $this->assertEquals($app_type["$appTypesF_MinTime"], $this->app_type_edit["$appTypesF_MinTime"] );   
        $this->assertEquals($app_type["$appTypesF_MaxTime"], $this->app_type_edit["$appTypesF_MaxTime"] );           
        $this->assertEquals($app_type["$appTypesF_NumberApp"],$this->app_type_edit["$appTypesF_NumberApp"] );
        $this->assertEquals($app_type["$appTypesF_Tariff"],   $this->app_type_edit["$appTypesF_Tariff"] );
        $this->assertEquals($app_type["$appTypesF_IsPublic"], $this->app_type_edit["$appTypesF_IsPublic"] );
        $this->assertEquals($app_type["$appTypesF_IsMulty"], $this->app_type_edit["$appTypesF_IsMulty"] );
		$this->assertEquals($app_type["$appTypesF_AppComment"], $this->app_type_edit["$appTypesF_AppComment"] ); 

		$rezult=appTypeTest::DeleteAppType($id);
 }
  
 public function testdeleteAppType()
 {include("variables_DB.php");
 $id=appTypeTest::AddAppType($this->app_type);
    $this->id=$id;
    $rez=app_type_bl::deleteAppType($this->id);
    $app_type_list=app_type_bl::getAppTypeListAll();
    $this->assertEquals(count($app_type_list), '0' );
    $app_type=app_type_bl::getAppTypeById($this->id);
    $this->assertEquals($app_type["$appTypesF_Id"],'');
 }
 
public function testcheckDateTimeForAppType()
 {include("variables_DB.php");
 $id=appTypeTest::AddAppType($this->app_type);
  $time_now_php=mktime();
  //test min time
  $check_time_php= strtotime("+2 hour", $time_now_php);
  $date_check=date("Y-m-d",$check_time_php);
  $time_check=date("H:i",$check_time_php);
 
   $rez_valid=app_type_bl::checkDateTimeForAppType($id,$date_check,$time_check);
   $this->assertEquals($rez_valid['rezult'], '1' );
   
  $check_time_php= strtotime("+30 minute", $time_now_php);
  $date_check=date("Y-m-d",$check_time_php);
  $time_check=date("H:i",$check_time_php);
 
  $rez_valid=app_type_bl::checkDateTimeForAppType($id,$date_check,$time_check);
  $this->assertNotEquals($rez_valid['rezult'], '1' );
  $this->assertEquals($rez_valid['focus'], $appTypesF_MinTime );
  
  //test max time
     $check_time_php= strtotime("+6 day", $time_now_php);
  $date_check=date("Y-m-d",$check_time_php);
  $time_check=date("H:i",$check_time_php);
 
   $rez_valid=app_type_bl::checkDateTimeForAppType($id,$date_check,$time_check);
   $this->assertEquals($rez_valid['rezult'], '1' );
   
  $check_time_php= strtotime("+8 day", $time_now_php);
  $date_check=date("Y-m-d",$check_time_php);
  $time_check=date("H:i",$check_time_php);
 
  $rez_valid=app_type_bl::checkDateTimeForAppType($id,$date_check,$time_check);
  $this->assertNotEquals($rez_valid['rezult'], '1' );
  $this->assertEquals($rez_valid['focus'], $appTypesF_MaxTime );
   $rezult=appTypeTest::DeleteAppType($id);
 }

 public function testgetAppTypeListForClientSelect()
 {include("variables_DB.php");

  $id1=appTypeTest::AddAppType($this->app_type);
 $id2=appTypeTest::AddAppType($this->app_type_edit);

  $app_type_list=app_type_bl::getAppTypeListForClientSelect(); 
          $rezult=appTypeTest::DeleteAppType($id1); 
        $rezult=appTypeTest::DeleteAppType($id2);
        
 //TODO $this->assertEquals(count($app_type_list), '1' );//
      
  
 }

 
//-----------------------------------------------------------------------------------------------------------------
    protected function tearDown()
    {
        $_SESSION['org_code']=$this->session_temp;
    }
//-----------------------------------------------------------------------------------------------------------------
    
public function AddAppType($app_type='null')
{
    	include("variables_DB.php");
    	$org_code=$_SESSION['org_code'];
    	if($app_type=='null'){
    			$app_type=array();	
    $random=generateRandom();
	$app_type["$appTypesF_Name"]="testapp".$random;
	$app_type["$appTypesF_Time"]="40";
	$app_type["$appTypesF_Color"]='#ffffff';
	$app_type["$appTypesF_AppComment"]='test app';
	$app_type["$appTypesF_MinTime"]=2;
	$app_type["$appTypesF_MaxTime"]=7;
	$app_type["$appTypesF_NumberApp"]=10;
	$app_type["$appTypesF_Tariff"]=99;
	$app_type["$appTypesF_IsPublic"]=1;
	$app_type["$appTypesF_IsMulty"]=1;
    	}
$sql="insert into $tableAppTypes (
       $appTypesF_Name,
       $appTypesF_Time,
       $appTypesF_Color,
       $appTypesF_AppComment,
       $appTypesF_MinTime,
       $appTypesF_MaxTime,
       $appTypesF_NumberApp,
       $appTypesF_Tariff,
       $appTypesF_IsPublic,
       $appTypesF_IsMulty,
	   $field_org_code
       
       ) 
values(
'".$app_type["$appTypesF_Name"]."',
".$app_type["$appTypesF_Time"].",
'".$app_type["$appTypesF_Color"]."',
'".$app_type["$appTypesF_AppComment"]."',
".$app_type["$appTypesF_MinTime"].",
".$app_type["$appTypesF_MaxTime"].",
".$app_type["$appTypesF_NumberApp"].",
".$app_type["$appTypesF_Tariff"].",
".$app_type["$appTypesF_IsPublic"].",
".$app_type["$appTypesF_IsMulty"].",
'$org_code'
)
 ";
//print_r($sql);
//exit;
$rez=mysql_query($sql);
$id=mysql_insert_id();
        return $id;
}
public function DeleteAppType($id)
{
   	include("variables_DB.php");
	    	$org_code=$_SESSION['org_code'];
	
	$sql="delete from $tableAppTypes  
          where $appTypesF_Id=".$id." and $field_org_code = '$org_code' ";

    $rez=mysql_query($sql);
    return true;
    }   
}


?>