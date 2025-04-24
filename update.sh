#!/bin/bash


validn() 
{
    z=1;
    while [ $z -eq 1 ]
    	do
	        if [ "$v" = 'y' ] || [ "$v" = 'Y' ] ; then

		        z=2;
		        retval=2;

	        elif [ "$v" = 'n' ] || [ "$v" = 'N' ] ; then

		        z=2;
		        retval=1;

	        else

		        echo -n "\n Enter [y/n] : " ;
		        read v;
		        z=1;
	        fi

        done;
}


c=1;
while [ $c -eq 1 ] 
    do
		clear;
		echo "\n	################ Sitting Register #############\n" ;
		echo "Enter the Postgres Database name : ";
		read dbname;
		pgdbname=$( echo "$dbname" )

		init_file=$pgdbname"_init_new"

		# schema_name=_${pgdbname}

		ecourtisdb_ip='localhost';
		ecourtisdb_name=$pgdbname;
		ecourtisdb_user='postgres';
		ecourtisdb_pass='';
		username="postgres";

		chmod -R 777 sw/*;

		swname='swecourtis';
		swnametwo='periphery';


		file1="/home/court/swecourtis/periphery/Sitting Register";

	    # if  [ ! -d "$file1" ] ; then

	    	cp -r sw/* /home/court/$swname/$swnametwo/. >/dev/null 2>&1 
			chown -R www-data:www-data /home/court/$swname/$swnametwo;
			chmod -R 777 /home/court/$swname/$swnametwo;

	    # fi

	    psql -U $username -p 5432 -d $pgdbname -h localhost -f sql_one.sql 
	    psql -U $username -p 5432 -d $pgdbname -h localhost -f sql_two.sql 

	    echo  " \n  *************************************************  \n ";

		echo "$pgdbname Completed....! press y to continue.....: "; 

		read v;  
		validn "$v"

		if [ $retval -eq 1 ]; then
		echo "-------------------------";
		exit;
		fi


done;
	
