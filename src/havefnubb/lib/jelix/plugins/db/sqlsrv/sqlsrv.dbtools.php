<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package    jelix
 * @subpackage db_driver
 * @author     Yann Lecommandoux
 * @contributor Julien, Laurent Jouanneau
 * @copyright  2008 Yann Lecommandoux, 2010 Julien, 2017 Laurent Jouanneau
 * @link      http://jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
class sqlsrvDbTools extends jDbTools{
	protected $dbmsStyle=array('/^\s*(#|\-\- )/','/;\s*$/');
	protected $typesInfo=array(
		'bool'=>array('tinyint','boolean',0,1,null,null),
		'boolean'=>array('tinyint','boolean',0,1,null,null),
		'bit'=>array('bit','integer',0,1,null,null),
		'tinyint'=>array('tinyint','integer',0,255,null,null),
		'smallint'=>array('smallint','integer',-32768,32767,null,null),
		'mediumint'=>array('int','integer',-8388608,8388607,null,null),
		'integer'=>array('int','integer',-2147483648,2147483647,null,null),
		'int'=>array('int','integer',-2147483648,2147483647,null,null),
		'bigint'=>array('bigint','numeric','-9223372036854775808','9223372036854775807',null,null),
		'serial'=>array('int','integer',-2147483648,2147483647,null,null),
		'bigserial'=>array('bigint','numeric','-9223372036854775808','9223372036854775807',null,null),
		'autoincrement'=>array('int','integer',-2147483648,2147483647,null,null),
		'bigautoincrement'=>array('bigint','numeric','-9223372036854775808','9223372036854775807',null,null),
		'float'=>array('float','float',null,null,null,null),
		'money'=>array('money','float',null,null,null,null),
		'smallmoney'=>array('smallmoney','float',null,null,null,null),
		'double precision'=>array('real','float',null,null,null,null),
		'double'=>array('real','float',null,null,null,null),
		'real'=>array('real','float',null,null,null,null),
		'number'=>array('real','decimal',null,null,null,null),
		'binary_float'=>array('real','float',null,null,null,null),
		'binary_double'=>array('real','decimal',null,null,null,null),
		'numeric'=>array('numeric','decimal',null,null,null,null),
		'decimal'=>array('decimal','decimal',null,null,null,null),
		'dec'=>array('decimal','decimal',null,null,null,null),
		'date'=>array('date','date',null,null,10,10),
		'time'=>array('time','time',null,null,8,16),
		'datetime'=>array('datetime','datetime',null,null,19,23),
		'datetime2'=>array('datetime2','datetime',null,null,19,27),
		'datetimeoffset'=>array('datetimeoffset','datetime',null,null,19,34),
		'smalldatetime'=>array('smalldatetime','datetime',null,null,19,19),
		'timestamp'=>array('datetime','datetime',null,null,19,19),
		'utimestamp'=>array('integer','integer',0,2147483647,null,null),
		'year'=>array('integer','year',null,null,2,4),
		'interval'=>array('datetime','datetime',null,null,19,19),
		'char'=>array('char','char',null,null,0,0),
		'nchar'=>array('nchar','char',null,null,0,0),
		'varchar'=>array('varchar','varchar',null,null,0,0),
		'varchar2'=>array('varchar','varchar',null,null,0,0),
		'nvarchar'=>array('nvarchar','varchar',null,null,0,0),
		'nvarchar2'=>array('nvarchar','varchar',null,null,0,0),
		'character'=>array('varchar','varchar',null,null,0,0),
		'character varying'=>array('varchar','varchar',null,null,0,0),
		'name'=>array('varchar','varchar',null,null,0,64),
		'longvarchar'=>array('varchar','varchar',null,null,0,0),
		'string'=>array('varchar','varchar',null,null,0,0),
		'tinytext'=>array('text','text',null,null,0,255),
		'text'=>array('text','text',null,null,0,0),
		'ntext'=>array('ntext','text',null,null,0,0),
		'mediumtext'=>array('text','text',null,null,0,0),
		'longtext'=>array('text','text',null,null,0,0),
		'long'=>array('text','text',null,null,0,0),
		'clob'=>array('text','text',null,null,0,0),
		'nclob'=>array('text','text',null,null,0,0),
		'tinyblob'=>array('varbinary','blob',null,null,0,255),
		'blob'=>array('varbinary','blob',null,null,0,65535),
		'mediumblob'=>array('varbinary','blob',null,null,0,16777215),
		'longblob'=>array('varbinary','blob',null,null,0,0),
		'bfile'=>array('varbinary','blob',null,null,0,0),
		'bytea'=>array('varbinary','varbinary',null,null,0,0),
		'binary'=>array('binary','binary',null,null,0,8000),
		'varbinary'=>array('varbinary','varbinary',null,null,0,8000),
		'raw'=>array('varbinary','varbinary',null,null,0,2000),
		'long raw'=>array('varbinary','varbinary',null,null,0,0),
		'image'=>array('image','varbinary',null,null,0,0),
		'enum'=>array('varchar','varchar',null,null,0,65535),
		'set'=>array('varchar','varchar',null,null,0,65535),
		'xmltype'=>array('varchar','varchar',null,null,0,65535),
		'xml'=>array('xml','text',null,null,0,0),
		'point'=>array('varchar','varchar',null,null,0,16),
		'line'=>array('varchar','varchar',null,null,0,32),
		'lsed'=>array('varchar','varchar',null,null,0,32),
		'box'=>array('varchar','varchar',null,null,0,32),
		'path'=>array('varchar','varchar',null,null,0,65535),
		'polygon'=>array('varchar','varchar',null,null,0,65535),
		'circle'=>array('varchar','varchar',null,null,0,24),
		'cidr'=>array('varchar','varchar',null,null,0,24),
		'inet'=>array('varchar','varchar',null,null,0,24),
		'macaddr'=>array('integer','integer',0,0xFFFFFFFFFFFF,null,null),
		'bit varying'=>array('varchar','varchar',null,null,0,65535),
		'arrays'=>array('varchar','varchar',null,null,0,65535),
		'complex types'=>array('varchar','varchar',null,null,0,65535),
	);
	protected $keywordNameCorrespondence=array(
		'current_date'=>'DATEFROMPARTS(DATEPART(year,GETDATE()),DATEPART(month,GETDATE()),DATEPART(day,GETDATE()))',
		'current_time'=>'TIMEFROMPARTS(DATEPART(hour,GETDATE()),DATEPART(minute,GETDATE()),DATEPART(second,GETDATE()),0,0)',
		'systimestamp'=>'GETDATE()',
		'sysdate'=>'GETDATE()',
		'localtime'=>'TIMEFROMPARTS(DATEPART(hour,GETDATE()),DATEPART(minute,GETDATE()),DATEPART(second,GETDATE()),0,0)',
		'localtimestamp'=>'GETDATE()',
	);
	protected $functionNameCorrespondence=array(
		'curdate'=>'DATEFROMPARTS(DATEPART(year,GETDATE()),DATEPART(month,GETDATE()),DATEPART(day,GETDATE()))',
		'current_date'=>'DATEFROMPARTS(DATEPART(year,GETDATE()),DATEPART(month,GETDATE()),DATEPART(day,GETDATE()))',
		'curtime'=>'TIMEFROMPARTS(DATEPART(hour,GETDATE()),DATEPART(minute,GETDATE()),DATEPART(second,GETDATE()),0,0)',
		'current_time'=>'TIMEFROMPARTS(DATEPART(hour,GETDATE()),DATEPART(minute,GETDATE()),DATEPART(second,GETDATE()),0,0)',
		'now'=>'GETDATE()',
		'current_timestamp'=>'GETDATE()',
		'date'=>'DATEFROMPARTS(DATEPART(year,%!p),DATEPART(month,%!p),DATEPART(day,%!p))',
		'dayofmonth'=>'day(%!p)',
		'localtime'=>'GETDATE()',
		'localtimestamp'=>'GETDATE()',
		'utc_date'=>'DATEFROMPARTS(DATEPART(year,GETUTCDATE()),DATEPART(month,GETUTCDATE()),DATEPART(day,GETUTCDATE()))',
		'utc_time'=>'TIMEFROMPARTS(DATEPART(hour,GETUTCDATE()),DATEPART(minute,GETUTCDATE()),DATEPART(second,GETUTCDATE()),0,0)',
		'utc_timestamp'=>'GETUTCDATE()',
		'time'=>'TIMEFROMPARTS(DATEPART(hour,%!p),DATEPART(minute,%!p),DATEPART(second,%!p),0,0)',
		'hour'=>'DATEPART(hour,GETDATE())',
		'minute'=>'DATEPART(minute,GETDATE())',
		'second'=>'DATEPART(second,GETDATE())',
		'extract'=>'!extractDateConverter',
		'date_part'=>'!extractDateConverter',
	);
	protected function extractDateConverter($parametersString){
		return 'datepart('.$parametersString.')';
	}
	public function encloseName($fieldName){
		return '['.$fieldName.']';
	}
	public function getFieldList($tableName,$sequence='',$schemaName=''){
		$results=array();
		$pkeys=array();
		$rs=$this->_conn->query('EXEC sp_pkeys ' . $tableName);
		while($line=$rs->fetch()){
			$pkeys[]=$line->COLUMN_NAME;
		}
		unset($line);
		$rs=$this->_conn->query('EXEC sp_columns ' . $tableName);
		while($line=$rs->fetch()){
			$field=new jDbFieldProperties();
			$field->name=$line->COLUMN_NAME;
			$field->type=$line->TYPE_NAME;
			$field->length=$line->LENGTH;
			if($field->type=='int identity'){
				$field->type='int';
				$field->autoIncrement=true;
			}
			if($field->type=='bit'){
				$field->type='int';
			}
			if($line->IS_NULLABLE=='No'){
				$field->notNull=false;
			}
			$field->hasDefault=($line->COLUMN_DEF!=='');
			$field->default=$line->COLUMN_DEF;
			if(in_array($field->name,$pkeys)){
				$field->primary=true;
			}
			$results[$line->COLUMN_NAME]=$field;
		}
		return $results;
	}
}
