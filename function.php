<?php

/*
*	Created by Vigneswara Baarathi
*	Function for retrieving data from database and populating table
*
*/

/*function retrieve_data($team_filter,$duration,$limit_s,$limit_e){

if ($team_filter == "dt.branch"){
	$one_title = "Branch";
//	$one_content = $result['branch'];
	
	
}else {
		$one_title = "Manager";
	//$one_content = $result['manager'];
}

	echo "<table class=\"table table-striped  table-hover\">
  					<tr>
  						<th>#</th>
  						<th>$one_title</th>
  						<th>$ Revenue</th>
  						<th>Nominal AA Margin</th>
  						<th>E2E Margin</th>
  						<th>Due > 90 days</th>
  						<th>Adj E2E Margin</th>
  						<th>Cust. Satisfaction</th>
  					</tr>	
";

	if(isset($limit_s)&& isset($limit_e)){
		$limit = " LIMIT $limit_s, $limit_e";
		
				
	} else {
		$limit ="";
		$limit_s = 1;
		}
	$query_name_sum = "SELECT
nm.manager_name,
dt.manager,
dn.branch_name,
dt.branch,
SUM(dt.revenue) AS r_revenue,
((SUM(dt.revenue)-SUM(dt.nominal))/SUM(dt.revenue)*100) AS nominal,
((SUM(dt.revenue)-(SUM(dt.nominal)+SUM(dt.acv)-SUM(dt.acv_n)+SUM(dt.acn)-SUM(dt.acn_n)+SUM(dt.acs)-SUM(dt.acs_n)+SUM(dt.acw)-SUM(dt.acw_n)+SUM(dt.opt)-SUM(dt.opt_n)))/SUM(dt.revenue)*100) AS e2e,
SUM(dt.over_due) AS over_due,
((SUM(dt.revenue)-SUM(dt.over_due)-(SUM(dt.nominal)+SUM(dt.acv)-SUM(dt.acv_n)+SUM(dt.acn)-SUM(dt.acn_n)+SUM(dt.acs)-SUM(dt.acs_n)+SUM(dt.acw)-SUM(dt.acw_n)+SUM(dt.opt)-SUM(dt.opt_n)))/SUM(dt.revenue)*100) AS a_e2e,
(SUM(dt.revenue)*((SUM(dt.revenue)-SUM(dt.over_due)-(SUM(dt.nominal)+SUM(dt.acv)-SUM(dt.acv_n)+SUM(dt.acn)-SUM(dt.acn_n)+SUM(dt.acs)-SUM(dt.acs_n)+SUM(dt.acw)-SUM(dt.acw_n)+SUM(dt.opt)-SUM(dt.opt_n)))/SUM(dt.revenue)*100))AS ranking
FROM $duration dt, names nm, teams dn 
WHERE nm.manager = dt.manager
AND dn.branch = dt.branch
AND dt.revenue > 0.1
GROUP BY $team_filter
ORDER BY ranking DESC
"."$limit";
	$exe_query= mysql_query($query_name_sum);
	
	
	$count = 1;
	
	while($result= mysql_fetch_array($exe_query)){
	
		if ($team_filter == "dt.branch"){

	$one_content = $result['branch_name'];
	
	
}else {

	$one_content = $result['manager_name'];
}

	/*Calculate 90 Days Over Due
	
	$over_due = number_format($result['over_due']);
	if ($over_due>0){
		$over_due_styled = "<span class=\"label label-important center-col\">$ $over_due</span>";
	}else $over_due_styled="<img src=\"img/tick.png\">";
	

	
	echo "<tr>
  						<td>$count</td>
  						<td>$one_content</td>
  						<td>$ ".number_format($result['r_revenue'])."</td>
  						<td>".round($result['nominal'],2)." %</td>
  						<td>".round($result['e2e'],2). " %</td>
  						<td>$over_due_styled</td>
  						<td>".round($result['a_e2e'],2)." %</td>
  						<td class=\"faded-text\">Coming Soon</td>
  					<!--	<td class=\"center-col\"><button class=\"btn btn-mini btn-info\" type=\"button\">View</button></td> -->
  						
  					</tr>";
  					$count++;
		
	}
	echo "</table>";
} */


/* FUNCTION TO CHECK NEW DATE*/

function check_date(){
	$query = "SELECT date FROM check_date";
	$result = mysql_query($query);
	$last_date = mysql_fetch_array($result);
	$last_registered_date = $last_date['date'];
	
	if ($last_registered_date!=date("Y-m-d")){
			header("Location:lib/index.php");
		} 
	
}

/* FUNCTION TO START DATE BASED ON TODAYS DATE EITHER 6 OR 12 MONTHS*/

function get_start_date($today,$duration){

return date("F Y", strtotime("-$duration months,$today"));
}


?>