class cRoad{
	// aNumberBoard=[];
	// aRoadBoard = {1:[],2:[],3:[]};
	// aNumberArray=[];
	// aRoadArray = {1:[],2:[],3:[]};
	// nHeight = 0;
	constructor() {
		this.aNumberBoard=[];
		this.aRoadBoard = {1:[],2:[],3:[]};
		this.aNumberArray=[];
		this.aRoadArray = {1:[],2:[],3:[]};
		this.nHeight = 0;
	}
	// 預設先做出順序
	// 之後放到棋盤時再考慮往右推的部分
	

	static fnDrawPic(aArray,nHeight){
		let len = aArray.length;
		let aBoard = {};
		let aLast = {"x":0, "y":0};
		let aTemp = JSON.parse(JSON.stringify(aArray)); // deep copy
		let nCollisionTimes = 0;
		for(let i=0;i<len;i++){
			aLast = aTemp[i];
			if(aLast.y === 0){
				nCollisionTimes = 0;
			}

			if(aLast.draw === undefined || aLast.draw === false)
			{
				// 如果超過高度就橫向延長
				if(aLast.y >= nHeight)
				{
					nCollisionTimes++;
				}
				// 如果遇到從前邊長過來的就轉彎
				else if(aBoard[aLast.x] !== undefined && aBoard[aLast.x][aLast.y] !== undefined)
				{
					nCollisionTimes++;				
				}
				// 如果前面轉彎過就總之跟著轉彎
				else if(nCollisionTimes>0)
				{
					nCollisionTimes++;
				}
			}
			
			aLast.x += nCollisionTimes;
			aLast.y -= nCollisionTimes;

			if(aBoard[aLast.x] === undefined){
				aBoard[aLast.x] = [];
			}
			aBoard[aLast.x][aLast.y] = aLast.bResult;
			aTemp[i] = aLast;
		}
		let aReturn = {'aPrintBoard':aBoard,'aPrintRoad':aTemp};

		return aReturn;
	}

	// this for 大路
	fnInsert(aResult,sNo=''){
		let bBpair = false;
		let bPpair = false;
		let bDraw = false;
		let aLastData;
		let bResult;
		let aLast;
		let nLength = this.aNumberArray.length;

		// 存檔
		if(this.aNumberArray[nLength-1] === undefined){
			aLastData = {"x":-1,"y":0,"bResult":undefined};
		}
		else{
			aLastData = this.aNumberArray[nLength-1];
		}
		
		// if(aResult['DRAW'] !== undefined)
		if(aResult['0'] == 'DRAW')
		{
			bDraw = true;
			aLast = aLastData;
			bResult = aLastData.bResult;
			if(aLastData.x === -1){
				aLast.x = 0;
			}
		}
		else
		{
			bDraw = false;
			// if(aResult['BW'] !== undefined)
			if(aResult['0'] == 'BW')
			{
				bResult = true;
			}
			// else if(aResult['PW'] !== undefined)
			else if(aResult['0'] == 'PW')
			{
				bResult = false;
			}
			aLast = this.fnLastPosition(aLastData,bResult);
			if(aLastData.draw === true && aLastData.bResult === undefined){
				aLast.x = aLastData.x;
				aLast.y = aLastData.y;
			}
		}
		
		// 只有value可能會繼承  其他屬性都是獨立的
		let aPush = {"x":aLast.x,"y":aLast.y,"bResult":bResult,"draw":bDraw,"sNo":sNo};
		for(let i=1;i<aResult.length;i++){
			aPush[aResult[i]] = true;
		}
		this.aNumberArray.push(aPush);
		if(this.aNumberBoard[aLast.x] === undefined){
			this.aNumberBoard[aLast.x] = [];
		}
		this.aNumberBoard[aLast.x][aLast.y] = bResult;
		// this.aNumberBoard[nLastX].push(bResult);

		if(aResult['0'] != 'DRAW'){
		// if(aResult['DRAW'] === undefined){
			// 處理各路
			for(let i=1;i<=3;i++){			
				bResult = this.fnRoad(nLength,i);
				// aLast = this.fnLastPosition(aLastData,bResult);
				// this.aRoadArray[i].push({"x":aLast.x,"y":aLast.y,"bResult":bResult,"sNo":sNo});
				// this.aRoadArray[i][aLast.x][aLast.y] = bResult;
			}
		}		
	}

	fnLastPosition(aLast,bResult){
		let nLastX;
		let nLastY;
		if((aLast.bResult === true && bResult === true) || (aLast.bResult === false && bResult === false))
		{
			nLastX = aLast.x;
			nLastY = aLast.y + 1;
			// 如果nLastX也undefined就會報錯
			// 如果預訂的格子有東西就往右上挪
			// if(nLastY > this.nHeight || (this.aNumberBoard[nLastX][nLastY] !== undefined))
			// {
			// 	nLastX += 1;
			// 	nLastY -= 1;
			// }
		}
		else
		{
			nLastX = aLast.x + 1;
			nLastY = 0;
		}
		return {"x":nLastX,"y":nLastY};
	}

	// offset=1=>大眼路  =2=>小路  =3=>小強路
	fnRoad(nBigLastDataKey,nOffset,sNo=''){
		let aBigLastData;
		let bResult;
		let aLast;
		let aLastData;

		if(this.aRoadArray[nOffset][this.aRoadArray[nOffset].length-1] === undefined){
			aLastData = {"x":-1,"y":0,"bResult":undefined};
		}
		else{
			aLastData = this.aRoadArray[nOffset][this.aRoadArray[nOffset].length-1];
		}

		aBigLastData = this.aNumberArray[nBigLastDataKey];
		if(this.aNumberBoard[nOffset] === undefined || this.aNumberBoard[nOffset][1] === undefined){
			if(this.aNumberBoard[nOffset+1] === undefined || this.aNumberBoard[nOffset+1][0] === undefined){
				return;
			}
		}

		if(aBigLastData.y === 0){
			bResult = this.fnFlatCheck(aBigLastData,nOffset);
		}
		else{
			bResult = this.fnExistCheck(aBigLastData,nOffset) || this.fnFallingCheck(aBigLastData,nOffset);
		}

		aLast = this.fnLastPosition(aLastData,bResult);
		this.aRoadArray[nOffset].push({"x":aLast.x,"y":aLast.y,"bResult":bResult,"sNo":sNo});
		if(this.aRoadBoard[nOffset][aLast.x] === undefined){
			this.aRoadBoard[nOffset][aLast.x] = [];
		}
		this.aRoadBoard[nOffset][aLast.x][aLast.y] = bResult;
	}

	fnFlatCheck(aLastData,nOffset){
		if(this.aNumberBoard[aLastData.x-1].length === this.aNumberBoard[aLastData.x-nOffset-1].length){
			return true;
		}
		return false;
	}

	fnExistCheck(aLastData,nOffset){
		if(this.aNumberBoard[aLastData.x-nOffset][aLastData.y] !== undefined){
			return true;
		}
		return false;
	}

	fnFallingCheck(aLastData,nOffset){
		if(	this.aNumberBoard[aLastData.x-nOffset][aLastData.y] === undefined && 
			this.aNumberBoard[aLastData.x-nOffset][aLastData.y-1] === undefined){
			return true;
		}
		return false;
	}
}

