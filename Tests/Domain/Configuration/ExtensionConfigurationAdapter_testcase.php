<?php

class Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter_testcase extends Tx_Extbase_BaseTestcase {
	


	protected $settingsFixture;
	
	public function setUp() {
		$this->settingsFixture = array ( 'listConfig' => array ( 'test' => array (
				'data' => array(
					'backend' => 'mysql',
					'datasource' => array (
						'host' => 'localhost',
						'username' => 'username',
						'password' => 'password',
						'database' => 'database'
					),
					'query' => array (
						'from' => array (
							'10' => array(
								'table' => 'table1',
								'alias' => 'alias1'
							),
							'20' => array(
								'table' => 'table2',
								'alias' => 'alias2'
							)
						),
						
						'join' => array (
							'10' => array (
								'table' => 'table1',
								'alias' => 'alias',
								'_typoScriptNodeValue' => 'INNER',
								'on' => array (
									'field' => 'field',
									'value' => 'value'
								)
							),
							
						),
						
						'where' => array (
							'and' => array (
								'10' => array (
									'field' => 'A',
									'value' => 'AA',
									'comparator' => 'lt'
								),
								'20' => array (
									'_typoScriptNodeValue' => 'OR',
									'10' => array (
										'field' => 'B',
										'value' => 'BB',
									),
									'20' => array ( 
										'field' => 'C',
										'value' => 'CC',
										'comparator' => 'eq',
									)
								)
							)
							
						),
						
						'mapping' => array (
							'property1' => 'field1',
							'property2' => 'field2',
						)
					)
				)
				)
				)
		);
	}
	
	public function testDataConfigurationType() {
		$adapter = new Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter($this->settingsFixture);
		
		$data = $adapter->getDataConfiguration('test');
		$this->assertTrue( $data instanceof Tx_PtExtlist_Domain_Configuration_DataConfiguration);
	}
	
	public function testDataConfiguration() {
		$adapter = new Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter($this->settingsFixture);
		
		$data = $adapter->getDataConfiguration('test');
		
		$backendType = $data->getBackendType();
		$host = $data->getHost();
		$username = $data->getUsername();
		$password = $data->getPassword();
		$source = $data->getSource();
		
		$this->assertEquals($backendType, 'mysql');
		$this->assertEquals($host, 'localhost');
		$this->assertEquals($username, 'username');
		$this->assertEquals($password, 'password');
		$this->assertEquals($source, 'database');
	}
	
	public function testSelectConfigurationType() {
		$adapter = new Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter($this->settingsFixture);
		
		$select = $adapter->getSelectQueryConfiguration('test');
		
		$this->assertTrue($select instanceof Tx_PtExtlist_Domain_Configuration_Query_Select);
	}
	
	public function testSelectConfiguration() {
		$adapter = new Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter($this->settingsFixture);
		
		$select = $adapter->getSelectQueryConfiguration('test');

		$fields = $select->getFields();
		
		$this->assertTrue( array_key_exists('field1', $fields) );
		$this->assertTrue( array_key_exists('field2', $fields) );
	}
	
	public function testFromConfigurationType() {
		$adapter = new Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter($this->settingsFixture);
		
		$from = $adapter->getFromQueryConfiguration('test');
		
		$this->assertTrue($from instanceof Tx_PtExtlist_Domain_Configuration_Query_From);
	}
	
	public function testFromConfiguration() {
		$adapter = new Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter($this->settingsFixture);
		
		$from = $adapter->getFromQueryConfiguration('test');
		
		$tables = $from->getTables();

		$this->assertTrue( array_key_exists('table1',$tables) );
		$this->assertEquals( $tables['table1'], 'alias1');
		
		$this->assertTrue( array_key_exists('table2',$tables) );
		$this->assertEquals( $tables['table2'], 'alias2');
	}
	
	public function testFromConfigurationSqlOverride() {
		$this->settingsFixture['listConfig']['test']['data']['query']['from']['_typoScriptNodeValue'] = 'table1, table2';
		$adapter = new Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter($this->settingsFixture);
		
		$from = $adapter->getFromQueryConfiguration('test');
			
		$this->assertEquals($from->getSql(), 'table1, table2');
	}
	
	public function testJoinConfigurationType() {
		$adapter = new Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter($this->settingsFixture);
		
		$join = $adapter->getJoinQueryConfiguration('test');
		
		$this->assertTrue($join instanceof Tx_PtExtlist_Domain_Configuration_Query_Join);
	}
	
	public function testJoinConfiguration() {
		$adapter = new Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter($this->settingsFixture);
		
		$join = $adapter->getJoinQueryConfiguration('test');

		$tables = $join->getTables();
		
		$this->assertTrue(array_key_exists('table1', $tables));
		
		$this->assertEquals($tables['table1']['alias'], 'alias');
		$this->assertEquals($tables['table1']['onField'], 'field');
		$this->assertEquals($tables['table1']['onValue'], 'value');
	}
	
	public function testJoinConfigurationSqlOverride() {
		$this->settingsFixture['listConfig']['test']['data']['query']['join']['_typoScriptNodeValue'] = 'table1 as t1 ON bla = foo';		
		$adapter = new Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter($this->settingsFixture);
		
		$join = $adapter->getJoinQueryConfiguration('test');
		
		$this->assertEquals($join->getSql(), 'table1 as t1 ON bla = foo');
		
		
	}
	
	public function testWhereConfigurationType() {
		$adapter = new Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter($this->settingsFixture);
		
		$where = $adapter->getWhereQueryConfiguration('test');
		
		$this->assertTrue( $where instanceof Tx_PtExtlist_Domain_Configuration_Query_Where);
	}
	
	public function testWhereConfiguration() {
		$adapter = new Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter($this->settingsFixture);
		
		$where = $adapter->getWhereQueryConfiguration('test');
		
		$this->assertFalse($where->isSql());
		
		$conditions = $where->getConditions();
		
		$this->assertTrue($conditions instanceof Tx_PtExtlist_Domain_Configuration_Query_And);
		$this->assertTrue($conditions[0] instanceof Tx_PtExtlist_Domain_Configuration_Query_Condition);
		$this->assertTrue($conditions[1] instanceof Tx_PtExtlist_Domain_Configuration_Query_Or);
		
		$field = $conditions[0]->getField();
		$value = $conditions[0]->getValue();
		$comp = $conditions[0]->getComparator();
		
		$this->assertEquals($field, 'A');
		$this->assertEquals($value, 'AA');
		$this->assertEquals($comp, 'lt');
		
		$conditions = $conditions[1];
		$this->assertTrue($conditions[0] instanceof Tx_PtExtlist_Domain_Configuration_Query_Condition);
		$this->assertTrue($conditions[1] instanceof Tx_PtExtlist_Domain_Configuration_Query_Condition);
		
		$field = $conditions[0]->getField();
		$value = $conditions[0]->getValue();
		$comp = $conditions[0]->getComparator();
		
		$this->assertEquals($field, 'B');
		$this->assertEquals($value, 'BB');
		$this->assertEquals($comp, 'eq');
		
		$field = $conditions[1]->getField();
		$value = $conditions[1]->getValue();
		$comp = $conditions[1]->getComparator();
		
		$this->assertEquals($field, 'C');
		$this->assertEquals($value, 'CC');
		$this->assertEquals($comp, 'eq');

	}
	
	public function testWhereConfigurationSqlOverride() {
		$this->settingsFixture['listConfig']['test']['data']['query']['where']['_typoScriptNodeValue'] = 'table1.id = bla AND table1.value = foo';		
		$adapter = new Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter($this->settingsFixture);
		
		$where = $adapter->getWhereQueryConfiguration('test');
		
		$this->assertTrue($where->isSql());
		$this->assertEquals($where->getSql(), 'table1.id = bla AND table1.value = foo');
	}
	
}

?>