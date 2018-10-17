<?php

  function Connection()
  {

    global $config;

    return new PDO( "pgsql:dbname=".$config['database'].";host=".$config['host'], $config['username'], $config['password']);

  }

  function Execute( $params)
  {

    $db = Connection();

    $stmt = $db->prepare( $params['sql']); 
    $stmt->execute( $params['params']);
    $data = $stmt->fetchAll( PDO::FETCH_BOTH);
    $stmt->closeCursor();

    unset( $db);

    return ( $data);

  }


  global $config;

  $up = explode( " ", $argv[3]);

  $config = array(
      'host'      => $argv[1],
      'port'      => '5432',
      'database'    => $argv[2],
      'username'      => $up[0],
      'password'  => $up[1],
    );

  if ( isset( $argv[4]))
  {
    $tables = explode( " ", $argv[4]);

    foreach ( $tables as $value) 
    {
        $params['sql'] = "select count(*) from " . $value;
        $params['params'] = array();
        $rows_count = Execute( $params);

        echo "Tabla: " . $value . " registros: " . number_format( $rows_count[0][0]) . "\n";
    }

  }
  else
  {

    $params['sql'] = "select table_name from information_schema.tables where table_schema = 'public' order by table_name asc";
    $params['params'] = array();
    $rows = Execute( $params);

    foreach ( $rows as $value) 
    {
        $params['sql'] = "select count(*) from " . $value['table_name'];
        $params['params'] = array();
        $rows_count = Execute( $params);

        echo "Tabla: " . $value['table_name'] . " registros: " . number_format( $rows_count[0][0]) . "\n";
    }

  }
  

echo "FIN\n";