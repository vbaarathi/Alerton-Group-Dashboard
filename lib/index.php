<?php
/*Initialize System*/
require "../db.php";
require "../function.php";

//CHECK UNAUTHORISED ACCESS BEFORE PROCEEDING
$query = "SELECT date FROM check_date";
	$result = mysql_query($query);
	$last_date = mysql_fetch_array($result);
	$last_registered_date = $last_date['date'];
	
	if ($last_registered_date==date("Y-m-d")){
			echo "Unauthorised action. Please Leave this page. <a href=\"../index.php\">Click here</a>" ;	
		} 	else {
		
			//EMPTY ALL TABLES
			truncate_table();
			
			//INSERT ALERTON PROJECTS AND CONTRACTING 
			
			insert_job_data("aa_job");
			
			insert_job_data("acn_job");
			
			insert_job_data("acs_job");
			
			insert_job_data("acv_job");
			
			insert_job_data("acw_job");
			
			insert_job_data("opt_job");
			
			//INSERT OVER DUE
			
			insert_overdue_data("over_due");
			
			//INSERT SERVICE DATA
			
			insert_service_data("aa_serv_trans");
			
			insert_service_data("aa_serv_arch_trans");
			
			//DISTRIBUTE SERVICE DATA
			
			distribute_service_data(6,"service_data_six");
			
			distribute_service_data(12,"service_data_twelve");
			
			process_service_data("6","service_data_six");
			
			process_service_data("12","service_data_twelve");
			
			
			process_filtered_data(6, "filtered_data");
			
			process_filtered_data(6, "filtered_data_six");
			
			process_filtered_data(12, "filtered_data_twelve"); 
			
			//INSERT CONTRACTIG HOURS
			
			insert_contract_hours("aa_hours","1030");
			
			insert_contract_hours("acn_hours","2020");
			
			insert_contract_hours("acs_hours","5050");
			
			insert_contract_hours("acv_hours","3030");
			
			insert_contract_hours("acw_hours","6060");
			



			$update_date="UPDATE check_date SET date=CURDATE() WHERE id = 1";
			mysql_query($update_date);
			
			header("Location:../index.php");
			
			}

//FUNCTION TO TRUNCATE ALL TABLES IN DATABASE

function truncate_table(){
	$truncate_table_query = "TRUNCATE `aa_job`;
TRUNCATE acn_job;
TRUNCATE acs_job;
TRUNCATE acv_job;
TRUNCATE acw_job;
TRUNCATE filtered_data;
TRUNCATE filtered_data_six;
TRUNCATE filtered_data_twelve;
TRUNCATE service_trans;
TRUNCATE service_data;
TRUNCATE service_data_six;
TRUNCATE service_data_twelve;
TRUNCATE service_data_process;
TRUNCATE opt_job;
TRUNCATE over_due;
TRUNCATE service_;
TRUNCATE contract_hours";


	//CODE TO EXECUTE MULTIPLE QUERIES ENDED WITH SEMICOLON
$queries = preg_split("/;+(?=([^'|^\\\']*['|\\\'][^'|^\\\']*['|\\\'])*[^'|^\\\']*[^'|^\\\']$)/",$truncate_table_query); 
foreach ($queries as $query){ 
   if (strlen(trim($query)) > 0) mysql_query($query); 
}

	
}

/* FUNCTION TO INSERT ALL COST INFORMATION INTO DATABASE*/

function insert_job_data($filename){
	$csv = "$filename".".csv";
	$fin = fopen($csv,'r') or die ('READ FAILURE');
	$count = 1;
	while (($data=fgetcsv($fin,0,","))!==FALSE){

		if($count>1){
			$convert_date = $data[0];
			$converted_date = strtotime($convert_date);
			$formated_date = date('y-m-d',$converted_date);

			$query = "INSERT INTO $filename(trans_date,project,branch,manager,type,cost,doc_type) VALUES ('$formated_date','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','$data[6]')";
	
			mysql_query($query);
			$count++;
			}else $count++;

			}
		//	echo "$csv Records Inserted Into Database <br>";
	fclose($fin);
	
}

/* FUNCTION TO INSERT INVOICES OVER 90 DAYS DUE INTO DATABASE*/

function insert_overdue_data($filename){
	$csv = "$filename".".csv";
	$fin = fopen($csv,'r') or die ('READ FAILURE');
	$count = 1;
	while (($data=fgetcsv($fin,0,","))!==FALSE){

		if($count>1){
			$convert_date = $data[0];
			$converted_date = strtotime($convert_date);
			$formated_date = date('y-m-d',$converted_date);

			$query = "INSERT INTO $filename(trans_date,project,invoice,amount,branch,manager) VALUES ('$formated_date','$data[1]','$data[2]','$data[3]','$data[5]','$data[6]')";
	
			mysql_query($query);
			$count++;
			}else $count++;

			}
		//	echo "$csv Records Inserted Into Database <br>";
	fclose($fin);
	
}


/* FUNCTION TO INSERT ALL service INFORMATION INTO DATABASE*/

function insert_service_data($filename){
	$csv = "$filename".".csv";
	$fin = fopen($csv,'r') or die ('READ FAILURE');
	$count = 1;
	while (($data=fgetcsv($fin,0,","))!==FALSE){

		if($count>1){
			$convert_date = $data[10];
			$converted_date = strtotime($convert_date);
			$formated_date = date('y-m-d',$converted_date);

			$query = "INSERT INTO service_trans(project,branch,service_engineer,type,cost,doc_type,parent_key,type_two,cat,project_type,trans_date,rep,team_lead) VALUES ('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','$data[6]','$data[7]','$data[8]','$data[9]','$formated_date','$data[11]','$data[12]')";
	
			mysql_query($query);
			$count++;
			}else $count++;

			}
		//	echo "$csv Records Inserted Into Database <br>";
	fclose($fin);
	
}


function distribute_service_data($duration,$table){
	
	$stop_date = date("Y-m-d");
	$start_date = date("Y-m-1", strtotime("-$duration months,$stop_date"));
	
	$distribute_data = "INSERT INTO $table (project,service_engineer, rep, team_lead, branch)
SELECT project,service_engineer,rep,team_lead,branch FROM service_trans 
GROUP BY project,service_engineer,rep,team_lead;


/*100 - MAINTENANCE*/
CREATE TEMPORARY TABLE ".$table."temp_100
SELECT project, service_engineer, rep, team_lead, branch, ABS(SUM(cost)) AS cost FROM service_trans
WHERE trans_date BETWEEN \"$start_date\" AND \"$stop_date\"
AND (type_two = 'Z' AND project_type = 'SM' OR type_two = 'S' AND project_type = 'PM')
AND (type = 'IN' OR type = 'CN')
GROUP BY project, service_engineer, rep, team_lead, branch;

UPDATE $table st JOIN ".$table."temp_100 tt
ON (st.project = tt.project AND st.service_engineer = tt.service_engineer AND st.rep = tt.rep AND st.team_lead = tt.team_lead AND st.branch = tt.branch)
SET st.t_100 = tt.cost;


/*101 S. CALLS*/
CREATE TEMPORARY TABLE ".$table."temp_101
SELECT project, service_engineer, rep, team_lead, branch, ABS(SUM(cost)) AS cost FROM service_trans
WHERE trans_date BETWEEN \"$start_date\" AND \"$stop_date\"
AND type_two = 'S' 
AND (project_type = 'CA' OR   project_type = 'CN' OR project_type = 'CO')
AND (type = 'IN' OR type = 'CN')
GROUP BY project, service_engineer, rep, team_lead, branch;

UPDATE $table st JOIN ".$table."temp_101 tt
ON (st.project = tt.project AND st.service_engineer = tt.service_engineer AND st.rep = tt.rep AND st.team_lead = tt.team_lead AND st.branch = tt.branch)
SET st.t_101 = tt.cost;


/*102 Q.S. CALLS*/
CREATE TEMPORARY TABLE ".$table."temp_102
SELECT project, service_engineer, rep, team_lead, branch, ABS(SUM(cost)) AS cost FROM service_trans
WHERE trans_date BETWEEN \"$start_date\" AND \"$stop_date\"
AND type_two = 'S' 
AND project_type = 'SQ' 
AND (type = 'IN' OR type = 'CN')
GROUP BY project, service_engineer, rep, team_lead, branch;

UPDATE $table st JOIN ".$table."temp_102 tt
ON (st.project = tt.project AND st.service_engineer = tt.service_engineer AND st.rep = tt.rep AND st.team_lead = tt.team_lead AND st.branch = tt.branch)
SET st.t_102 = tt.cost;

/*103 SPECIAL PROJECTS*/
CREATE TEMPORARY TABLE ".$table."temp_103
SELECT project, service_engineer, rep, team_lead, branch, ABS(SUM(cost)) AS cost FROM service_trans
WHERE trans_date BETWEEN \"$start_date\" AND \"$stop_date\"
AND type_two = 'C' 
AND project_type = 'SP' 
AND (type = 'IN' OR type = 'CN')
GROUP BY project, service_engineer, rep, team_lead, branch;

UPDATE $table st JOIN ".$table."temp_103 tt
ON (st.project = tt.project AND st.service_engineer = tt.service_engineer AND st.rep = tt.rep AND st.team_lead = tt.team_lead AND st.branch = tt.branch)
SET st.t_103 = tt.cost;

/*109 WARRANTY*/
CREATE TEMPORARY TABLE ".$table."temp_109
SELECT project, service_engineer, rep, team_lead, branch, ABS(SUM(cost)) AS cost FROM service_trans
WHERE trans_date BETWEEN \"$start_date\" AND \"$stop_date\"
AND type_two = 'S' 
AND (project_type = 'WA' OR project_type = 'SW')
AND (type = 'IN' OR type = 'CN')
GROUP BY project, service_engineer, rep, team_lead, branch;

UPDATE $table st JOIN ".$table."temp_109 tt
ON (st.project = tt.project AND st.service_engineer = tt.service_engineer AND st.rep = tt.rep AND st.team_lead = tt.team_lead AND st.branch = tt.branch)
SET st.t_109 = tt.cost;


/*100_n MAINTENANCE*/
CREATE TEMPORARY TABLE ".$table."temp_100_n
SELECT project, service_engineer, rep, team_lead, branch, ABS(SUM(cost)) AS cost FROM service_trans
WHERE trans_date BETWEEN \"$start_date\" AND \"$stop_date\"
AND (type_two = 'Z' AND project_type = 'SM' OR type_two = 'S' AND project_type = 'PM')
AND (type != 'IN' OR type != 'CN')
GROUP BY project, service_engineer, rep, team_lead, branch;

UPDATE $table st JOIN ".$table."temp_100_n tt
ON (st.project = tt.project AND st.service_engineer = tt.service_engineer AND st.rep = tt.rep AND st.team_lead = tt.team_lead AND st.branch = tt.branch)
SET st.t_100_n = tt.cost;


/*101_n S. CALLS*/
CREATE TEMPORARY TABLE ".$table."temp_101_n
SELECT project, service_engineer, rep, team_lead, branch, ABS(SUM(cost)) AS cost FROM service_trans
WHERE trans_date BETWEEN \"$start_date\" AND \"$stop_date\"
AND type_two = 'S' 
AND (project_type = 'CA' OR   project_type = 'CN' OR project_type = 'CO')
AND (type != 'IN' OR type != 'CN')
GROUP BY project, service_engineer, rep, team_lead, branch;

UPDATE $table st JOIN ".$table."temp_101_n tt
ON (st.project = tt.project AND st.service_engineer = tt.service_engineer AND st.rep = tt.rep AND st.team_lead = tt.team_lead AND st.branch = tt.branch)
SET st.t_101_n = tt.cost;


/*102_n Q.S CALLS*/
CREATE TEMPORARY TABLE ".$table."temp_102_n
SELECT project, service_engineer, rep, team_lead, branch, ABS(SUM(cost)) AS cost FROM service_trans
WHERE trans_date BETWEEN \"$start_date\" AND \"$stop_date\"
AND type_two = 'S' 
AND project_type = 'SQ' 
AND (type != 'IN' OR type != 'CN')
GROUP BY project, service_engineer, rep, team_lead, branch;

UPDATE $table st JOIN ".$table."temp_102_n tt
ON (st.project = tt.project AND st.service_engineer = tt.service_engineer AND st.rep = tt.rep AND st.team_lead = tt.team_lead AND st.branch = tt.branch)
SET st.t_102_n = tt.cost;

/*103_n SPECIAL PROJECTS*/
CREATE TEMPORARY TABLE ".$table."temp_103_n
SELECT project, service_engineer, rep, team_lead, branch, ABS(SUM(cost)) AS cost FROM service_trans
WHERE trans_date BETWEEN \"$start_date\" AND \"$stop_date\"
AND type_two = 'C' 
AND project_type = 'SP' 
AND (type != 'IN' OR type != 'CN')
GROUP BY project, service_engineer, rep, team_lead, branch;

UPDATE $table st JOIN ".$table."temp_103_n tt
ON (st.project = tt.project AND st.service_engineer = tt.service_engineer AND st.rep = tt.rep AND st.team_lead = tt.team_lead AND st.branch = tt.branch)
SET st.t_103_n = tt.cost;

/*109 WARRANTY*/
CREATE TEMPORARY TABLE ".$table."temp_109_n
SELECT project, service_engineer, rep, team_lead, branch, ABS(SUM(cost)) AS cost FROM service_trans
WHERE trans_date BETWEEN \"$start_date\" AND \"$stop_date\"
AND type_two = 'S' 
AND (project_type = 'WA' OR project_type = 'SW')
AND (type != 'IN' OR type != 'CN')
GROUP BY project, service_engineer, rep, team_lead, branch;

UPDATE $table st JOIN ".$table."temp_109_n tt
ON (st.project = tt.project AND st.service_engineer = tt.service_engineer AND st.rep = tt.rep AND st.team_lead = tt.team_lead AND st.branch = tt.branch)
SET st.t_109_n = tt.cost;

";

//CODE TO EXECUTE MULTIPLE QUERIES ENDED WITH SEMICOLON
$queries = preg_split("/;+(?=([^'|^\\\']*['|\\\'][^'|^\\\']*['|\\\'])*[^'|^\\\']*[^'|^\\\']$)/", $distribute_data); 
foreach ($queries as $query){ 
   if (strlen(trim($query)) > 0) mysql_query($query); 
} 
}

function process_service_data($duration, $table){



$process_service_query = "/* SERVICE ENGINEER */
CREATE TEMPORARY TABLE ".$table."_temp_service
SELECT sd.service_engineer AS name ,
SUM(sd.t_100) AS t_100,
SUM(sd.t_101) AS t_101,
SUM(sd.t_102) AS t_102,
SUM(sd.t_103) AS t_103,
SUM(sd.t_109) AS t_109,
SUM(sd.t_100_n) AS t_100_n,
SUM(sd.t_101_n) AS t_101_n,
SUM(sd.t_102_n) AS t_102_n,
SUM(sd.t_103_n) AS t_103_n,
SUM(sd.t_109_n) AS t_109_n,
SUM(sd.t_100) + SUM(t_101) + SUM(t_102) + SUM(t_103) + SUM(t_109) as total_rev,
SUM(sd.t_100_n) + SUM(t_101_n) + SUM(t_102_n) + SUM(t_103_n) + SUM(t_109_n) AS total_cost
FROM $table sd
GROUP BY sd.service_engineer;

INSERT INTO service_data_process (name,t_100, t_101, t_102, t_103, t_109, t_100_n, t_101_n, t_102_n, t_103_n, t_109_n, total_rev, total_cost, over_due, fil, duration)
SELECT nm.manager_name, t_100, t_101, t_102, t_103, t_109, t_100_n, t_101_n, t_102_n, t_103_n, t_109_n, total_rev, total_cost, SUM(od.amount), '1',\"$duration\" 
FROM ".$table."_temp_service tt 
LEFT JOIN over_due od ON (tt.name = od.manager)
LEFT JOIN names nm ON (tt.name = nm.manager)
GROUP BY nm.manager_name;


/*REP */


CREATE TEMPORARY TABLE ".$table."_temp_rep
SELECT nm.manager AS name,
SUM(sd.t_100) AS t_100,
SUM(sd.t_101) AS t_101,
SUM(sd.t_102) AS t_102,
SUM(sd.t_103) AS t_103,
SUM(sd.t_109) AS t_109,
SUM(sd.t_100_n) AS t_100_n,
SUM(sd.t_101_n) AS t_101_n,
SUM(sd.t_102_n) AS t_102_n,
SUM(sd.t_103_n) AS t_103_n,
SUM(sd.t_109_n) AS t_109_n,
SUM(sd.t_100) + SUM(t_101) + SUM(t_102) + SUM(t_103) + SUM(t_109) as total_rev,
SUM(sd.t_100_n) + SUM(t_101_n) + SUM(t_102_n) + SUM(t_103_n) + SUM(t_109_n) AS total_cost
FROM $table sd
LEFT JOIN names_combo nm ON (sd.rep = nm.rep) 
GROUP BY nm.manager;

INSERT INTO service_data_process (name,t_100, t_101, t_102, t_103, t_109, t_100_n, t_101_n, t_102_n, t_103_n, t_109_n, total_rev, total_cost, over_due, fil, duration)
SELECT nc.manager_name, t_100, t_101, t_102, t_103, t_109, t_100_n, t_101_n, t_102_n, t_103_n, t_109_n, total_rev, total_cost, SUM(od.amount), '2',\"$duration\"
FROM ".$table."_temp_rep tt 
LEFT JOIN over_due od ON (tt.name = od.manager)
LEFT JOIN names_combo nc ON (tt.name = nc.manager)
GROUP BY name;

/*TEAM_LEAD*/

CREATE TEMPORARY TABLE ".$table."_temp_lead
SELECT nm.manager AS name,
SUM(sd.t_100) AS t_100,
SUM(sd.t_101) AS t_101,
SUM(sd.t_102) AS t_102,
SUM(sd.t_103) AS t_103,
SUM(sd.t_109) AS t_109,
SUM(sd.t_100_n) AS t_100_n,
SUM(sd.t_101_n) AS t_101_n,
SUM(sd.t_102_n) AS t_102_n,
SUM(sd.t_103_n) AS t_103_n,
SUM(sd.t_109_n) AS t_109_n,
SUM(sd.t_100) + SUM(t_101) + SUM(t_102) + SUM(t_103) + SUM(t_109) as total_rev,
SUM(sd.t_100_n) + SUM(t_101_n) + SUM(t_102_n) + SUM(t_103_n) + SUM(t_109_n) AS total_cost
FROM $table sd
LEFT JOIN names_combo nm ON (sd.team_lead = nm.rep) 
GROUP BY nm.manager;

INSERT INTO service_data_process (name,t_100, t_101, t_102, t_103, t_109, t_100_n, t_101_n, t_102_n, t_103_n, t_109_n, total_rev, total_cost, over_due, fil, duration)
SELECT nc.manager_name, t_100, t_101, t_102, t_103, t_109, t_100_n, t_101_n, t_102_n, t_103_n, t_109_n, total_rev, total_cost, SUM(od.amount), '3',\"$duration\" 
FROM ".$table."_temp_lead tt 
LEFT JOIN over_due od ON (tt.name = od.manager)
LEFT JOIN names_combo nc ON (tt.name = nc.manager)
GROUP BY name;";

//CODE TO EXECUTE MULTIPLE QUERIES ENDED WITH SEMICOLON
$queries = preg_split("/;+(?=([^'|^\\\']*['|\\\'][^'|^\\\']*['|\\\'])*[^'|^\\\']*[^'|^\\\']$)/", $process_service_query); 
foreach ($queries as $query){ 
   if (strlen(trim($query)) > 0) mysql_query($query); 
} 

}


/* FUNCTION TO INSERT DATA INTO NEW FILTERED DATA */

function process_filtered_data($duration, $table){

if ($table=="filtered_data"){
	$stop_date = "2012-12-31";
} else { $stop_date = date("Y-m-d");}



$start_date = date("Y-m-1", strtotime("-$duration months,$stop_date"));

//This is not used.
$due_danger_date = date("Y-m-1", strtotime("-6 months,$stop_date"));

		$process_data = "
INSERT INTO $table (manager, project, branch)
SELECT DISTINCT(manager), project, branch FROM aa_job ORDER BY manager;

CREATE TEMPORARY TABLE temp_over_due_group
SELECT a.project, a.manager, a.branch   
from over_due a left join filtered_data b 
on (a.project = b.project AND a.manager = b.manager AND a.branch = b.branch)
    where (b.project is null AND b.manager is null and b.branch is null)
GROUP BY a.project, a.manager, a.branch;

INSERT INTO $table (project, manager, branch)
SELECT project,manager,branch FROM temp_over_due_group;



CREATE TEMPORARY TABLE ".$table."_revenue
SELECT manager, project, SUM(cost) as revenue  FROM aa_job
WHERE (type = 'IN'OR type = 'CN')
AND trans_date BETWEEN \"$start_date\"AND \"$stop_date\"
GROUP BY project, manager;


UPDATE $table bt JOIN ".$table."_revenue tr 
ON (bt.project = tr.project AND bt.manager = tr.manager)
SET bt.revenue = tr.revenue;

CREATE TEMPORARY TABLE ".$table."_cost
SELECT manager, project, SUM(cost) as cost  FROM aa_job
WHERE type != 'IN'
AND trans_date BETWEEN \"$start_date\"AND \"$stop_date\"
GROUP BY project, manager;


UPDATE $table bt JOIN ".$table."_cost tr 
ON (bt.project = tr.project AND bt.manager = tr.manager)
SET bt.cost = tr.cost;

CREATE TEMPORARY TABLE ".$table."_acw
SELECT project, SUM(cost) as cost  FROM acw_job
WHERE (type = 'IN' or type = 'CN')
AND trans_date BETWEEN \"$start_date\"AND \"$stop_date\"
GROUP BY project;

UPDATE $table bt JOIN ".$table."_acw tr 
ON (bt.project = tr.project)
SET bt.acw = tr.cost;

CREATE TEMPORARY TABLE ".$table."_acv
SELECT project, SUM(cost) as cost  FROM acv_job
WHERE (type = 'IN' or type = 'CN')
AND trans_date BETWEEN \"$start_date\"AND \"$stop_date\"
GROUP BY project;

UPDATE $table bt JOIN ".$table."_acv tr 
ON (bt.project = tr.project)
SET bt.acv = tr.cost;


CREATE TEMPORARY TABLE ".$table."_acs
SELECT project, SUM(cost) as cost  FROM acs_job
WHERE (type = 'IN' or type = 'CN')
AND trans_date BETWEEN \"$start_date\"AND \"$stop_date\"
GROUP BY project;

UPDATE $table bt JOIN ".$table."_acs tr 
ON (bt.project = tr.project)
SET bt.acs = tr.cost;

CREATE TEMPORARY TABLE ".$table."_acn
SELECT project, SUM(cost) as cost  FROM acn_job
WHERE (type = 'IN' or type = 'CN')
AND trans_date BETWEEN \"$start_date\"AND \"$stop_date\"
GROUP BY project;

UPDATE $table bt JOIN ".$table."_acn tr 
ON (bt.project = tr.project)
SET bt.acn = tr.cost;

CREATE TEMPORARY TABLE ".$table."_opt
SELECT project, SUM(cost) as cost  FROM opt_job
WHERE (type = 'IN' or type = 'CN')
AND trans_date BETWEEN \"$start_date\"AND \"$stop_date\"
GROUP BY project;

UPDATE $table bt JOIN ".$table."_opt tr 
ON (bt.project = tr.project)
SET bt.opt = tr.cost;

CREATE TEMPORARY TABLE ".$table."_n_acw
SELECT project, SUM(cost) as cost  FROM acw_job
WHERE type != 'IN'
AND trans_date BETWEEN '$start_date'AND '$stop_date'
GROUP BY project;

UPDATE $table bt JOIN ".$table."_n_acw tr 
ON (bt.project = tr.project)
SET bt.acw_n = tr.cost;

CREATE TEMPORARY TABLE ".$table."_n_acv
SELECT project, SUM(cost) as cost  FROM acv_job
WHERE type != 'IN'
AND trans_date BETWEEN '$start_date'AND '$stop_date'
GROUP BY project;

UPDATE $table bt JOIN ".$table."_n_acv tr 
ON (bt.project = tr.project)
SET bt.acv_n = tr.cost;

CREATE TEMPORARY TABLE ".$table."_n_acs
SELECT project, SUM(cost) as cost  FROM acs_job
WHERE type != 'IN'
AND trans_date BETWEEN '$start_date'AND '$stop_date'
GROUP BY project;

UPDATE $table bt JOIN ".$table."_n_acs tr 
ON (bt.project = tr.project)
SET bt.acs_n = tr.cost;

CREATE TEMPORARY TABLE ".$table."_n_acn
SELECT project, SUM(cost) as cost  FROM acn_job
WHERE type != 'IN'
AND trans_date BETWEEN '$start_date'AND '$stop_date'
GROUP BY project;

UPDATE $table bt JOIN ".$table."_n_acn tr 
ON (bt.project = tr.project)
SET bt.acn_n = tr.cost;

CREATE TEMPORARY TABLE ".$table."_n_opt
SELECT project, SUM(cost) as cost  FROM opt_job
WHERE type != 'IN'
AND trans_date BETWEEN '$start_date' AND '$stop_date'
GROUP BY project;

UPDATE $table bt JOIN ".$table."_n_opt tr 
ON (bt.project = tr.project)
SET bt.opt_n = tr.cost;

/*OVERDUE CODE */

CREATE TEMPORARY TABLE ".$table."_over_due
SELECT project, manager, branch, SUM(amount) AS over_due
FROM over_due
GROUP BY project, manager, branch;

UPDATE $table dt JOIN ".$table."_over_due od ON (dt.project = od.project AND dt.manager = od.manager AND dt.branch = od.branch) SET dt.over_due = od.over_due;

";

//CODE TO EXECUTE MULTIPLE QUERIES ENDED WITH SEMICOLON
$queries = preg_split("/;+(?=([^'|^\\\']*['|\\\'][^'|^\\\']*['|\\\'])*[^'|^\\\']*[^'|^\\\']$)/", $process_data); 
foreach ($queries as $query){ 
   if (strlen(trim($query)) > 0) mysql_query($query); 
} 
	
}

/* FUNCTION TO INSERT ALL CONTRACTING HOURS INFORMATION INTO DATABASE*/

function insert_contract_hours($filename,$branch){
	$csv = "$filename".".csv";
	$fin = fopen($csv,'r') or die ('READ FAILURE');
	$count = 1;
	while (($data=fgetcsv($fin,0,","))!==FALSE){

		if($count>1){
			$convert_date = $data[2];
			$converted_date = strtotime($convert_date);
			$formated_date = date('y-m-d',$converted_date);

			$query = "INSERT INTO contract_hours(name,type,trans_date,h1,h2,h3,h4,h5,h6,h7,project,category,branch) VALUES ('$data[0]','$data[1]','$formated_date','$data[3]','$data[4]','$data[5]','$data[6]','$data[7]','$data[8]','$data[9]','$data[10]','$data[11]','$data[12]')";
	
			mysql_query($query);
			$count++;
			}else $count++;

			}
		//	echo "$csv Records Inserted Into Database <br>";
	fclose($fin);
	
}


?>