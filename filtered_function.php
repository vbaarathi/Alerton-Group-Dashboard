<?php

/*
*	Created by Vigneswara Baarathi
*	Function for retrieving data from database and populating table
*
*/



function retrieve_projects_data($team_filter,$duration,$limit_s,$limit_e){

if ($team_filter == "dt.branch"){
	$one_title = "Branch";
//	$one_content = $result['branch'];
	
	
}else {
		$one_title = "Manager";
	//$one_content = $result['manager'];
}

if ($duration == "filtered_data_six"){
	$cust_service_title = "";
	$cust_service_data = "";
}else {
		$cust_service_title = "<th>Cust. Satisfaction</th>";
		$cust_service_data = "<td class=\"faded-text\">Coming Soon</td>";
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
  						$cust_service_title
  					</tr>	
";

	if(isset($limit_s)&& isset($limit_e)){
		$limit = " LIMIT $limit_s, $limit_e";
		if ($limit_s == 0){
			$count = 1;
		}else $count = 16;
				
	} else {
		$limit ="";
		$limit_s = 1;
		$count = 1;
		}
	$query_name_sum = "SELECT 
nm.manager_name, 
tm.branch_name,
abs(sum(dt.revenue)) AS revenue,
abs(sum(dt.cost)) AS cost,
((abs(sum(dt.revenue))-abs(sum(dt.cost)))/abs(sum(dt.revenue)))*100 AS nominal,
ABS(SUM(dt.acn))+ABS(SUM(dt.acs))+ABS(SUM(dt.acv))+ABS(SUM(dt.acw))+(ABS(SUM(dt.opt))/3.2) AS contracting_revenue,
ABS(SUM(dt.acn_n))+ABS(SUM(dt.acs_n))+ABS(SUM(dt.acv_n))+ABS(SUM(dt.acw_n))+(ABS(SUM(dt.opt_n))/3.2) AS contracting_cost,
ABS(SUM(dt.over_due)) AS over_due,
(ABS(SUM(dt.revenue))*((ABS(SUM(dt.revenue))-ABS(SUM(dt.over_due))-(ABS(SUM(dt.cost))+ABS(SUM(dt.acv))-ABS(SUM(dt.acv_n))+ABS(SUM(dt.acn))-ABS(SUM(dt.acn_n))+ABS(SUM(dt.acs))-ABS(SUM(dt.acs_n))+ABS(SUM(dt.acw))-ABS(SUM(dt.acw_n))+ABS(SUM(dt.opt))-ABS(SUM(dt.opt_n))))/ABS(SUM(dt.revenue))*100)) AS ranking
FROM $duration dt, names nm, teams tm 
WHERE nm.manager = dt.manager
AND tm.branch = dt.branch
group by $team_filter
ORDER BY ranking DESC
"."$limit";

	$exe_query= mysql_query($query_name_sum);


	
	while($result= mysql_fetch_array($exe_query)){
		if ($result['revenue']>0.1){
			
		
			if ($team_filter == "dt.branch"){
	
				$one_content = $result['branch_name'];
				} else {
	
					$one_content = $result['manager_name'];
					}
	
			/*Calculate 90 Days Over Due*/
			
			$over_due = number_format($result['over_due']);
			if ($over_due>0){
				$over_due_styled = "<span class=\"label label-important center-col\">$ $over_due</span>";
			}else $over_due_styled="<img src=\"img/tick.png\">";
		
			/*Calculate e2e margine
			$total_revenue = $result['revenue'];
			$total_cost = $result['cost']+$result['contracting_revenue']-$result['contracting_cost'];
			$e2e_fig = (($total_revenue-$total_cost)/$total_revenue)*100;
			
			$a_e2e_fig = (($total_revenue-$result['over_due']-$total_cost)/$total_revenue)*100;
			*/
			
			$total_revenue = $result['revenue'];
			//$total_cost = $result['cost']+$result['contracting_revenue']-$result['contracting_cost'];
			$total_cost = $result['cost'];
			$total_cont_rev = $result['contracting_revenue'];
			$total_cont_cost = $result['contracting_cost'];
			$e2e_fig = (($total_revenue-$total_cost+$total_cont_rev-$total_cont_cost)/$total_revenue)*100;
			
			$a_e2e_fig = (($total_revenue-$result['over_due']-$total_cost+$total_cont_rev-$total_cont_cost)/$total_revenue)*100;
		
			echo "<tr>
		  						<td>$count</td>
		  						<td>$one_content</td>
		  						<td>$ ".number_format($result['revenue'])."</td>
		  						<td>".round($result['nominal'],2)." %</td>
		  						<td>".round($e2e_fig,2). " %</td>
		  						<td>$over_due_styled</td>
		  						<td>".round($a_e2e_fig,2)." %</td>
		  						$cust_service_data
		  					<!--	<td class=\"center-col\"><button class=\"btn btn-mini btn-info\" type=\"button\">View</button></td> -->
		  						
		  					</tr>";
		  					$count++;
				
			}
		}
		echo "</table>";
}


function retrieve_service_data($team_filter,$duration,$limit_s,$limit_e){

/*if ($team_filter == "dt.branch"){
	$one_title = "Branch";
//	$one_content = $result['branch'];
	
	
}else {
		$one_title = "Team Lead";
	//$one_content = $result['manager'];
}*/


	if(isset($limit_s)&& isset($limit_e)){
		$limit = " LIMIT $limit_s, $limit_e";
		
				
	} else {
		$limit ="";
		$limit_s = 1;
		}

switch($team_filter){
	case "service_engineer":
	
	$processed_filter = 1;
	
	$one_title = "Service Engineer";
	//$one_content = $result['service_engineer'];
	//$query_start = "tt.manager_name";
	/*$query_end = "FROM $duration sd, names tt
WHERE sd.service_engineer = tt.manager
GROUP BY sd.service_engineer"."$limit";*/
	break;
	
	case "team_lead":
		$processed_filter = 3;
	$one_title = "Team Lead";
	/*$one_content = $result['team_lead'];
	$query_start = "tt.name";
	$query_end = "FROM $duration sd, names_codes tt
WHERE sd.team_lead = tt.rep
GROUP BY sd.team_lead"."$limit";*/
	break;
	
	case "rep":
		$processed_filter = 2;
	$one_title = "Rep";
	/*$one_content = $result['rep'];
	$query_start = "tt.name";
	$query_end = "FROM $duration sd, names_codes tt
WHERE sd.rep = tt.rep
GROUP BY sd.rep"."$limit";*/
	break;
	
	case "team":
		$processed_filter = 1;
	$one_title = "Team";
	/*$one_content = $result['team'];
	$query_start = "tt.branch_name";
	$query_end = "FROM $duration sd, teams tt
WHERE sd.branch = tt.branch
GROUP BY sd.branch"."$limit";*/
	break;
	
}
	
	
	if ($duration == "service_data_twelve"){
		$processed_duration = "12";
	}else $processed_duration = "6";


	echo "<table class=\"table table-striped  table-hover\">
  					<tr>
  						<th>#</th>
  						<th>$one_title</th>
  						<th>Maintenance</th>
  						<th>S.Calls</th>
  						<th>Q.S.Calls</th>
  						<th>Special Projects</th>
  						
  						<th>TOTAL</th>
  						<th>Margin</th>
  						<th>Due > 90 days</th>
  						<th>Adj E2E Margin</th>
  						<th>Cust. Satisfaction</th>
  					</tr>	
";


	$query_name_sum = "SELECT * FROM service_data_process WHERE fil = '$processed_filter' AND duration = \"$processed_duration\"";

	$exe_query= mysql_query($query_name_sum);

	$count = 1;
	
	while($result= mysql_fetch_array($exe_query)){
		if ($result['total_rev']>0.1){
			//$one_content = $result['title'];
			
	/*		switch($query_start){
				case "service_engineer":
				$one_content = $result['service_engineer'];
				break;
				
				case "team_lead":
				$one_content = $result['team_lead'];
				break;
				
				case "rep":
				$one_content = $result['rep'];
				break;
				
				case "team":
				$one_content = $result['team'];
				break;
				
			} */
		
				
			//Calculate 90 Days Over Due
			
			$over_due = number_format($result['over_due']);
			if ($over_due>0){
				$over_due_styled = "<span class=\"label label-important center-col\">$ $over_due</span>";
			}else $over_due_styled="<img src=\"img/tick.png\">";
		
			/*Calculate e2e margine*/
			$total_revenue = $result['total_rev'];
			$total_cost = $result['total_cost'];
			$e2e_fig = (($total_revenue-$total_cost)/$total_revenue)*100;
			
			$a_e2e_fig = (($total_revenue-$result['over_due']-$total_cost)/$total_revenue)*100;
		
			echo "<tr>
		  						<td>$count</td>
		  						<td>".$result['name']."</td>
		  						<td>$ ".number_format($result['t_100'])."</td>
		  						<td>$ ".number_format($result['t_101'])."</td>
		  						<td>$ ".number_format($result['t_102'])."</td>
		  						<td>$ ".number_format($result['t_103'])."</td>
		  						
		  						<td>$ ".number_format($total_revenue)."</td>
		  						<td>".round($e2e_fig,1). " %</td>
		  						<td>$over_due_styled</td>
		  						<td>".round($a_e2e_fig,1). "</td>
		  						<td>Coming Soon</td>
		  					
		  						
		  					</tr>";
		  					$count++;
				
			}
		}
		echo "</table>";
}

function retrieve_contracting_data($team_filter,$duration,$limit_s,$limit_e){

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
  						<th>Project Manager</th>
  						<th>Revenue</th>
  						<th>Nominal Margin</th>
  						<th>Hours of overtime</th>
  						<th>Ratio of Material</th>
  						
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
tm.branch_name,
abs(sum(dt.revenue)) AS revenue,
abs(sum(dt.cost)) AS cost,
((abs(sum(dt.revenue))-abs(sum(dt.cost)))/abs(sum(dt.revenue)))*100 AS nominal,
ABS(SUM(dt.acn))+ABS(SUM(dt.acs))+ABS(SUM(dt.acv))+ABS(SUM(dt.acw))+(ABS(SUM(dt.opt))/3.2) AS contracting_revenue,
ABS(SUM(dt.acn_n))+ABS(SUM(dt.acs_n))+ABS(SUM(dt.acv_n))+ABS(SUM(dt.acw_n))+(ABS(SUM(dt.opt_n))/3.2) AS contracting_cost,
ABS(SUM(dt.over_due)) AS over_due,
(ABS(SUM(dt.revenue))*((ABS(SUM(dt.revenue))-ABS(SUM(dt.over_due))-(ABS(SUM(dt.cost))+ABS(SUM(dt.acv))-ABS(SUM(dt.acv_n))+ABS(SUM(dt.acn))-ABS(SUM(dt.acn_n))+ABS(SUM(dt.acs))-ABS(SUM(dt.acs_n))+ABS(SUM(dt.acw))-ABS(SUM(dt.acw_n))+ABS(SUM(dt.opt))-ABS(SUM(dt.opt_n))))/ABS(SUM(dt.revenue))*100)) AS ranking
FROM $duration dt, names nm, teams tm 
WHERE nm.manager = dt.manager
AND tm.branch = dt.branch
group by $team_filter
ORDER BY ranking DESC
"."$limit";

	$exe_query= mysql_query($query_name_sum);

	$count = 1;
	
	while($result= mysql_fetch_array($exe_query)){
		if ($result['revenue']>0.1){
			
		
			if ($team_filter == "dt.branch"){
	
				$one_content = $result['branch_name'];
				} else {
	
					$one_content = $result['manager_name'];
					}
	
			/*Calculate 90 Days Over Due*/
			
			$over_due = number_format($result['over_due']);
			if ($over_due>0){
				$over_due_styled = "<span class=\"label label-important center-col\">$ $over_due</span>";
			}else $over_due_styled="<img src=\"img/tick.png\">";
		
			/*Calculate e2e margine*/
			$total_revenue = $result['revenue'];
			$total_cost = $result['cost']-$result['contracting_revenue']+$result['contracting_cost'];
			$e2e_fig = (($total_revenue-$total_cost)/$total_revenue)*100;
			
			$a_e2e_fig = (($total_revenue-$result['over_due']-$total_cost)/$total_revenue)*100;
		
			echo "<tr>
		  						<td></td>
		  						<td></td>
		  						<td></td>
		  						<td></td>
		  						<td></td>
		  						<td></td>
		  						
		  						
		  						<td>Coming Soon</td>
		  					
		  						
		  					</tr>";
		  					$count++;
				
			}
		}
		echo "</table>";
}

function retrieve_sales_data($team_filter,$duration,$limit_s,$limit_e){

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
  						<th>Name</th>
  						<th>Sales Budget (FY/TD)</th>
  						<th>Sales Budget (Q/TD)</th>
  						<th>Sales Budget (M/TD)</th>
  						<th>Margin History</th>
  						<th>360</th>
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
tm.branch_name,
abs(sum(dt.revenue)) AS revenue,
abs(sum(dt.cost)) AS cost,
((abs(sum(dt.revenue))-abs(sum(dt.cost)))/abs(sum(dt.revenue)))*100 AS nominal,
ABS(SUM(dt.acn))+ABS(SUM(dt.acs))+ABS(SUM(dt.acv))+ABS(SUM(dt.acw))+(ABS(SUM(dt.opt))/3.2) AS contracting_revenue,
ABS(SUM(dt.acn_n))+ABS(SUM(dt.acs_n))+ABS(SUM(dt.acv_n))+ABS(SUM(dt.acw_n))+(ABS(SUM(dt.opt_n))/3.2) AS contracting_cost,
ABS(SUM(dt.over_due)) AS over_due,
(ABS(SUM(dt.revenue))*((ABS(SUM(dt.revenue))-ABS(SUM(dt.over_due))-(ABS(SUM(dt.cost))+ABS(SUM(dt.acv))-ABS(SUM(dt.acv_n))+ABS(SUM(dt.acn))-ABS(SUM(dt.acn_n))+ABS(SUM(dt.acs))-ABS(SUM(dt.acs_n))+ABS(SUM(dt.acw))-ABS(SUM(dt.acw_n))+ABS(SUM(dt.opt))-ABS(SUM(dt.opt_n))))/ABS(SUM(dt.revenue))*100)) AS ranking
FROM $duration dt, names nm, teams tm 
WHERE nm.manager = dt.manager
AND tm.branch = dt.branch
group by $team_filter
ORDER BY ranking DESC
"."$limit";

	$exe_query= mysql_query($query_name_sum);

	$count = 1;
	
	while($result= mysql_fetch_array($exe_query)){
		if ($result['revenue']>0.1){
			
		
			if ($team_filter == "dt.branch"){
	
				$one_content = $result['branch_name'];
				} else {
	
					$one_content = $result['manager_name'];
					}
	
			/*Calculate 90 Days Over Due*/
			
			$over_due = number_format($result['over_due']);
			if ($over_due>0){
				$over_due_styled = "<span class=\"label label-important center-col\">$ $over_due</span>";
			}else $over_due_styled="<img src=\"img/tick.png\">";
		
			/*Calculate e2e margine*/
			$total_revenue = $result['revenue'];
			$total_cost = $result['cost']-$result['contracting_revenue']+$result['contracting_cost'];
			$e2e_fig = (($total_revenue-$total_cost)/$total_revenue)*100;
			
			$a_e2e_fig = (($total_revenue-$result['over_due']-$total_cost)/$total_revenue)*100;
		
			echo "<tr>
		  						<td></td>
		  						<td></td>
		  						<td></td>
		  						<td></td>
		  						<td></td>
		  						<td></td>
		  						<td></td>
		  						
		  						<td>Coming Soon</td>
		  					
		  						
		  					</tr>";
		  					$count++;
				
			}
		}
		echo "</table>";
}




?>