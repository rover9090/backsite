<?php
define('INS','Add');
define('UPT','Modify');
define('DEL','Delete');
define('SUCCESS','Success');
define('FAIL','Failed');
define('ALL','All');
define('PLEASESELECT','Please select');
define('PARAMSERR','Parameter exception');
define('EXPORTXLS','Export to Excel'); # 2019-10-24 YL
define('UNFILLED','Not filled in');
define('FORMATEERR','Format error');
define('NODATA','Check no data');
define('NODATAYET','No data yet');
define('RIGHTMSG','Confirmation message');
define('ERRORMSG','Error message');
define('MAINTENANCE','Website under maintenance');
define('MANYCLICK','Too much sent');

# Language
define('CHOSELANG','Select language');
# Paging
define('FIRSTPAGE','First page');
define('PREPAGE','Previous page');
define('NEXTPAGE','Next page');
define('BEFORETEN','First 10 pages');
define('NEXTTEN','Next 10 pages');
define('LASTPAGE','Last Page');
define('RECORD','Record'); #Record

define('ACCOUNT','Account');
define('NAME','Name');
define('KIND','Classification');
define('CREATETIME','CREATETIME');
define('UPDATETIME','Update time');
define('STARTTIME','Start Time');
define('ENDTIME','End Time');
define('OPERATE','OPERATE');
define('STATUS','Status');
define('SUBMIT','Submit');
define('CONFIRM','Confirm');
define('CANCEL','Cancel');
define('BACK','Back');
define('CLOSE','Close');
define('SEARCH','Query');
define('CSUBMIT','Confirm to submit');
define('CDELETE','Confirm deletion');
define('CBACK','Cancel return');
define('GOBACK','Go back to the previous page');

define('INSV','Added successfully');
define('UPTV','Modified successfully');
define('DELV','Delete successfully');
define('NOTICE','Temporary notice');

define('aONLINE', array(
	1 => array(
		'sText' =>'Online',
		'sSelect' =>'',
	),
	0 => array(
		'sText' =>'Offline',
		'sSelect' =>'',
	),
));

define('aMENU', array(
	'USERRANK' =>'Upgrade membership',
	'USERBANK' =>'Bank account number',
	'USERBANKADD' =>'Add a bank account',
	'USERLINK' =>'My team',
	'BROADCASR' =>'Latest News',
	'PROMO' =>'Share link',
	'MISSOINS' =>'Special area',
	'MYMISSOINS' =>'My mission',
	'CHATROOM' =>'Online customer service',
	'RECHARGE' =>'Recharge',
	'WITHDRAWAL' =>'Withdrawal',
	'INFO' =>'Personal Information',
	'CHANGETRANSPWD' =>'Modify transaction password',
	'CHANGEPWD' =>'Modify login password',
	'FUNDSRECORD' =>'Transaction record',
	'SERVICE' =>'Contact us',
	'DOWNLOAD' =>'Download link',

	'INDEX' =>'Home',
	'MISSION' =>'Mission',
	'SERVICE' =>'Customer Service',
	'CENTER' =>'Member',

));

define('aDAYTEXT', array(
	'YESTERDAY' =>'yesterday',
	'TODAY' =>'Today',
	'LASTWEEK' =>'Last WEEK',
	'THISWEEK' =>'This week',
	'LASTMONTH' =>'Last Month',
	'THISMONTH' =>'This month',
));

# Various picture errors #
define('aIMGERROR',array(
	'ERROR' =>'Picture upload failed, please re-upload (if this error occurs repeatedly, please replace the picture)',
	'TYPE' =>'Picture format does not match, please upload again',
	'SIZE' =>'Picture size does not match, please upload again',
	'INISIZE' =>'The picture size exceeds the ini limit, please re-upload',
	'FORMSIZE' =>'The image size exceeds the limit of the form, please upload again',
	'PARTIAL' =>'Only part of the picture has been uploaded, please re-upload',
	'NOFILE' =>'The picture has not been uploaded, please re-upload',
	'TMPDIR' =>'The picture cannot be found in the temporary folder, please upload it again',
	'CANTWRITE' =>'Image file writing failed, please re-upload',
	'LEASTONE' =>'Please upload at least one picture',
));

define('aLOGNUMS', $aSystem['aLogNums']);

define('aSITEMENU',array(
	'INDEX' =>'Home',
	'ANNOUNCE' =>'Latest News',
	'PICTURE' =>'Activity Silhouette',
	'ACTIVITY' =>'Activity Registration',
	'RESERVE' =>'Ask about appointment',
	'MALL' =>'Around the palace and temple',
	'ORDER' =>'My record',
));

define('aRECORDKIND',array(
	'RESERVE' =>'Ask about appointment',
	'ACTIVITY' =>'Activity Registration',
	'MALLLOG' =>'Around the palace and temple',
	'MALLLIT' =>'Lighting record',
));

define('aBETCONTENT',array(
	'BW' =>'Banker Wins',
	'PW' =>'Player Wins',
	'DRAW' =>'Tie',
	'BP' =>'Banker Pair',
	'PP' =>'Player Pair',
	'SIX' =>'Super Six',
));
?>