<?php

/**

 * @package Count Unique Visits

 * @version 1.0

 */

 

/*

Plugin Name: Count Unique Visits

Plugin URI: http://infoeinternet.altervista.org/

Description: This plugin count the number of unique visitors of a website

Author: Matteo Veroni

Version: 1.0

Author URI: http://matteoveroni.altervista.org/



Infoeinternet (Wordpress Plugin)

Copyright (C) 2013 Matteo Veroni

 

This program is free software: you can redistribute it and/or modify

it under the terms of the GNU General Public License as published by

the Free Software Foundation, either version 3 of the License, or

(at your option) any later version.

 

This program is distributed in the hope that it will be useful,

but WITHOUT ANY WARRANTY; without even the implied warranty of

MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the

GNU General Public License for more details.

 

You should have received a copy of the GNU General Public License

along with this program. If not, see <http://www.gnu.org/licenses/>.

*/



function count_unique_visits(){


	try{

		$pdo=new PDO('mysql:host=localhost;dbname=db_name', 'db_user', 'password');

		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$pdo->exec('SET NAMES "utf8"');

	}catch(PDOException $e){

		exit();

	}


	// - Read the ip of the current visitor
	$current_user_ip=$_SERVER['REMOTE_ADDR'];


	// - For local installation on xampp 
	if($current_user_ip=='::1')

		$current_user_ip='127.0.0.1';


	// - Print on screen the ip of the user
	$output='Your IP is: <strong>'.$current_user_ip.'</strong>  ';


	// - Query that select all the ip adresses in my database
	try{

		

		$sql='SELECT ipa,ipb,ipc,ipd FROM iptable WHERE 1';


		$result=$pdo->query($sql);

		

	}catch(Exception $e){

		exit();

	}



	// - set to false ip alredy visit
	$ip_already_visit=FALSE;



	// - count the number of result of the select query (number of "ip/unic visitors")
	$num_tot_ip=$result->rowCount();



	// - Foreach ip already present in my database
	foreach($result as $ipstored){

		

		// - if the current user have already visited my webpage -> then...
		if($current_user_ip==($ipstored['ipa'].'.'

		                    .$ipstored['ipb'].'.'

							.$ipstored['ipc'].'.'

							.$ipstored['ipd'])){					

			

			// - This is the ip of a user that have already visited the website
			$ip_already_visit=TRUE;

			

			// - stop to cycle cause now we are sure that the user have alredy visited our site
			break;

			

		}

	}



	// - If the user have not already visited this site -> then..
	if($ip_already_visit==FALSE){
			

		$ipabcd=explode('.',$current_user_ip);


		// - Query that insert the ip of the new visitors in our ip database
		try{


			$sql= 'INSERT INTO iptable(ipa,ipb,ipc,ipd)

			       VALUES ('.$ipabcd[0].',

						   '.$ipabcd[1].',

						   '.$ipabcd[2].',

						   '.$ipabcd[3].')';
	   

			$result=$pdo->query($sql);


			// - increase by one the number of "ip/unic visitors" cause we've just inserted
			//   a new visitor in the db

			$num_tot_ip++;


		}catch(Exception $e){

			exit();

		}

	}


	// - Print on screen the number of ip of visitors
	$output.= ' / Numero di visite uniche totali sito: <strong>'.$num_tot_ip.'</strong>';



	// - Save the number of unic visitors on a file (counter.txt)
	$handle=fopen("counter.txt",'w');


	$current=fwrite($handle,$num_tot_ip);


	fclose($handle);


	return $output;


}







