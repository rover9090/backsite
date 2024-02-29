var BaseCmdLogic;
(function (BaseCmdLogic) {
	/**
	*
	@class MainSrvCmdLogic
	*/
	var MainSrvCmdLogic = (function ()
	{
		/**
		@constructor
		@param {PhotonController.PhotonIf} pi photon interface
		*/
		function MainSrvCmdLogic(pi)
		{
			this._account = '';
			this._password = '';
			this._uid = 0;
			this._group_id = 0;
			this._pi = pi;
			this._MainCmdFuncObj = null;
		}//constructor function end

		//Connect
		MainSrvCmdLogic.prototype.RunConnect = function(account,password,uid,group_id,token)
		{
			console.log(account,password,token,uid,group_id);
			this._account = account;
			this._password = password;
			this._token = token;
			this._uid = parseInt(uid);
			this._group_id = parseInt(group_id);
			this._pi.connect();//對main server做連線
		}

		//Send message
		MainSrvCmdLogic.prototype.SendMessage = function(message,target_uid=0)
		{
			this._pi.sendData(102,this._group_id,message,target_uid);//對main server送出訊息
			//alert('1');
			// console.log("sendData group_id:",this._group_id," message:",message, " uid:",target_uid);
		}

		MainSrvCmdLogic.prototype.RefreshSend2 = function()
		{
			console.log('hi2');
			this._pi.sendData(102,this._group_id,'',0);//對main server送出訊息
		}

		//Join group
		MainSrvCmdLogic.prototype.JoinGroup = function(uid)
		{
			this._pi.sendData(104,uid,this._group_id);//對main server送出訊息
			// console.log("JoinGroup uid:",uid,"group_id:",this._group_id);
		}

		//leave group
		MainSrvCmdLogic.prototype.LeaveGroup = function(uid)
		{
			this._pi.sendData(105,uid,this._group_id);//對main server送出訊息
			// console.log("LeaveGroup uid:",uid,"group_id:",this._group_id);
		}

		//photon peer status callback function
		MainSrvCmdLogic.prototype.PeerStatusCallback= function(st, selfObj)
		{
			switch(st)
			{
				case Photon.PhotonPeer.StatusCodes.connect:
					console.log("伺服器 connect ref");
					console.log("send ",101,selfObj._account,'',1,selfObj._uid);
					RefreshSend();
					timeRefreshSend = setInterval(RefreshSend, 1000*30);
					selfObj._pi.sendData(101,selfObj._account,'',1,selfObj._uid);//送出驗證資訊給server

					if (selfObj._pi._ppr._isConnected === true)
					{
						if ($('input[name=nFirstJoin]').val() == '1')
						{
							// console.log("send ",104,selfObj._uid,selfObj._group_id);
							// selfObj._pi.sendData(104,selfObj._uid,selfObj._group_id); //告訴大家新加入
							selfObj._pi.sendData(104,selfObj.account,'',1,selfObj._uid); //告訴大家新加入
						}
						// 先註解
						// if ($('.JqNewAnnounce').val() != '')
						// {
						// 	selfObj._pi.sendData(102,selfObj._group_id,$('.JqNewAnnounce').val(),0); //告訴大家新公告
						// 	$('.JqNewAnnounce').val('');
						// }
					}
				break;
				case Photon.PhotonPeer.StatusCodes.connectFailed:
				case Photon.PhotonPeer.StatusCodes.error:
					// alert('1');
					console.log("伺服器 error");
					// location.reload();
					// RefreshSend();
					// $('.JqJumpMsgBox[data-showmsg=1]').find('.JqJumpMsgContentTxt').html(aJSDEFINE['CONNECTFAILED']);
					// $('.JqJumpMsgBox[data-showmsg=1]').addClass('active');
				break;
				case Photon.PhotonPeer.StatusCodes.disconnect:
					// alert('2');
					console.log("伺服器 disconnect");
					// location.reload();
					// RefreshSend();
					// $('.JqJumpMsgBox[data-showmsg=1]').find('.JqJumpMsgContentTxt').html(aJSDEFINE['DISCONNECT']);
					// $('.JqJumpMsgBox[data-showmsg=1]').addClass('active');
				break;
				case Photon.PhotonPeer.StatusCodes.timeout:
					// alert('3');
					console.log("伺服器 timeout");
					// location.reload();
					// RefreshSend();
					// $('.JqJumpMsgBox[data-showmsg=1]').find('.JqJumpMsgContentTxt').html(aJSDEFINE['CONNECTTIMEOUT']);
					// $('.JqJumpMsgBox[data-showmsg=1]').addClass('active');
				break;
				case Photon.PhotonPeer.StatusCodes.connectClosed:
					// alert('4');
					console.log("伺服器 connectClosed");
					// location.reload();
					// RefreshSend();
					// $('.JqJumpMsgBox[data-showmsg=1]').find('.JqJumpMsgContentTxt').html(aJSDEFINE['CONNECTCLOSED']);
					// $('.JqJumpMsgBox[data-showmsg=1]').addClass('active');
				break;
				default:
					// alert('5');
					console.log("伺服器 connectting:"+st);
			}
		}

		//photon peer reponse callback function
		MainSrvCmdLogic.prototype.ResponseCallback= function(vals, selfObj)
		{
			selfObj._MainCmdFuncObj(vals, selfObj._pi);
			// console.log("ResponseCallback vals0 :",vals[0]);
			// console.log("ms event:"+vals);

		}
		MainSrvCmdLogic.prototype.SetGameCmdFunc= function(FuncObj)
		{
			this._MainCmdFuncObj = FuncObj;
		}

		//photon peer event callback function
		MainSrvCmdLogic.prototype.EventCallback= function(vals, selfObj)
		{
			// console.log("EventCallback vals0 :",vals[0]);
			// console.log("ms event:"+vals);
		}

		return MainSrvCmdLogic;
	}());//class end
	BaseCmdLogic.MainSrvCmdLogic = MainSrvCmdLogic;
})(BaseCmdLogic || (BaseCmdLogic = {}));//namespace end
