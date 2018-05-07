<?php
	function start_transaction()
	{
		mysql_query("START TRANSACTION");
	}

	function commit()
	{
		mysql_query("COMMIT");
	}

	function rollback()
	{
		mysql_query("ROLLBACK");
	}


?>