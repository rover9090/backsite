<?PHP
namespace App\Http\Controllers;

// use Validator; #SAME
use Illuminate\Support\Facades\Validator; # SAME Validator
use Illuminate\Validation\Rules\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Module\ShareData;
use App\Models\User;
use App\Enum\ESexType;#自己加ㄉ資料夾
use Image;# 安裝套件  composer require intervention/image
use App\Models\Mind;

class AdminController extends Controller
{
	public $page = "admin";

	//自我介紹頁面
	public function editUserPage()
	{
		$User = $this->GetUserData();
		if(!$User)
		{
			//如果找不到使用者，就回到首頁
			return redirect('/');
		}
		$name = 'user';

		$binding = [
			'title' => ShareData::TITLE,
			'page' => $this->page,
			'name' => $name,
			'user' => $User,
			'result' => '',
		];
		return view('admin.edituser', $binding);
	}

	//處理自我介紹資料
	public function editUserProcess()
	{
		$User = $this->GetUserData();
		if(!$User)
		{
			//如果找不到使用者，就回到首頁
			return redirect('/');
		}
		$name = 'user';
	
		//接收輸入資料
		$input = request()->all();
	
		//驗證規則
		$rules = [
			//性別
			'sex' => [
				'required',
				'integer',
				'in:'.ESexType::MALE.','.ESexType::FEMALE,
			],
			//身高
			'height' => [
				'required',
				'numeric',
				'min:1',
			],
			//體重
			'weight' => [
				'required',
				'numeric',
				'min:1',
			],
			//興趣
			'interest' => [
				'required',
				'max:50'
			],
			//自我介紹
			'introduce' => [
				'required',
				'max:500'
			],
			//圖片
			'file' => [
				// File::image()
				// 	->max(10240)//10 MB
				'file',
				'image',
				'max:10240', 
			],
		];
		$messages = [
			'introduce.required' => '自我介紹為必填',
			// 'file.required' => '興趣為必填',
			'sex.required' => '性別為必填',
			'height.required' => '身高為必填',
			'weight.required' => '體重為必填',
			'interest.required' => '興趣為必填',
		];
		//驗證資料
		$validator = Validator::make($input, $rules,$messages);
	
		//接收網頁資料，不論驗證有沒有通過都要用到
		$User->nSex = $input['sex'];
		$User->nHeight = $input['height'];
		$User->nWeight = $input['weight'];
		$User->sInterest = $input['interest'];
		$User->sIntroduce = $input['introduce'];
	
		if(isset($input['file'])){
			Log::notice('file='.print_r($input['file'], true));
		}

		if($validator->fails())
		{
			$binding = [
				'title' => ShareData::TITLE,
				'page' => $this->page,
				'name' => $name,
				'user' => $User,
			];
			return view('admin.edituser', $binding)
					->withErrors($validator);
		}

		if(isset($input['file']))
		{
			//取得檔案物件
			$picture = $input['file'];
			//檔案副檔名
			$extension = $picture->getClientOriginalExtension();
			//產生隨機檔案名稱
			$filename = uniqid().'.'.$extension;
			//相對路徑
			$relative_path = 'images/user/'.$filename;
			// $relative_path = 'images\\user\\'.$filename; # 操你媽這樣寫不會過
			//取得public目錄下的完整位置
			$fullpath = public_path($relative_path);
			//裁切圖片
			$image = Image::make($picture)->fit(300, 300)->save($fullpath);
			//儲存圖片檔案相對位置
			$User->sPicture = $relative_path;
		}
	
		//將修改後的資料存入資料庫
		$User->save();
	
		$binding = [
			'title' => ShareData::TITLE,
			'page' => $this->page,
			'name' => $name,
			'user' => $User,
			'result' => 'success',
		];
		return view('admin.edituser', $binding)
				->withErrors($validator);
	}

	//心情隨筆列表頁面
	public function mindListPage()
	{
		Log::notice('取得心情隨筆列表');
		//先取得自己的資料
		$User = $this->GetUserData();
		//取得心情隨筆列表
		$mindPaginate = Mind::where('nUid', $User->nId)-> paginate(5);
		// $list = Mind::where('nUid', $User->nId)->get();
		$name = 'mind';

		//接收輸入資料
		$input = request()->all();

		$result = '';
		if(isset($input['result']))
		{
		    $result = $input['result'];
		}

		    
		$binding = [
			'title' => ShareData::TITLE,
			'page' => $this->page,
			'name' => $name,
			'user' => $User,
			// 'list' => $list,
			'mindPaginate' => $mindPaginate,
			'result' => $result,
		];
		return view('admin.mindlist', $binding);
	}

	//新增心情隨筆資料
	function addMindPage()
	{
		Log::notice('新增心情隨筆資料');
		//先取得自己的資料
		$User = $this->GetUserData();
		//取得心情隨筆列表
		$Mind = new Mind;
		$name = 'mind';
		$action = '新增';

		$binding = [
			'title' => ShareData::TITLE,
			'page' => $this->page,
			'name' => $name,
			'user' => $User,
			'mind' => $Mind,
			'action' => $action,
			'result' => '',
		];
		return view('admin.mind', $binding);
	}

	//編輯心情隨筆的動作
	function editMindProcess()
	{
		Log::notice('處理心情隨筆資料');
		$User = $this->GetUserData();
		if(!$User)
		{
			Log::notice('找不到使用者');
			//如果找不到使用者，就回到首頁
			return redirect('/');
		}
		$name = 'mind';

		//接收輸入資料
		$input = request()->all();

		//驗證規則
		$rules = [
			//內容
			'content' => [
				'required',
				'max:400'
			],
		];

		//驗證資料
		$validator = Validator::make($input, $rules);

		if($input['id'] == '')
		{
			//新增
			$action = '新增';
			$Mind = new Mind;
			$Mind->sContent = $input['content'];
		}
		else
		{
			//修改
			$action = '修改';
			//取得心情隨筆列表
			$Mind = Mind::where('nId', $input['id'])->where('nUid', $User->nId)->first();
			if(!$Mind)
			{
			    //如果找不到資料就回列表頁
			    return redirect('/admin/mind');
			}
			$Mind->sContent = $input['content'];
		}

		if($validator->fails())
		{
			$binding = [
				'title' => ShareData::TITLE,
				'page' => $this->page,
				'name' => $name,
				'user' => $User,
				'mind' => $Mind,
				'action' => $action,
				'result' => '',
			];
			return view('admin.mind', $binding)
				->withErrors($validator);
		}

		if($input['id'] == '')
		{
			$aInput["sContent"] = $Mind->sContent;
			$aInput["nUid"] = $User->nId;
			$aInput["nOnline"] = 1;
			Mind::create($aInput);
		}
		else
		{
			//修改
			$Mind->save();
		}

		//成功就轉回列表頁
		return redirect('/admin/mind/?result=success');    
	}
	
	//編輯心情隨筆資料
	function editMindPage($mind_id)
	{
		Log::notice('新增心情隨筆資料');
		//先取得自己的資料
		$User = $this->GetUserData();
		//取得心情隨筆列表
		$Mind = Mind::where('nId', $mind_id)->where('nUid', $User->nId)->first();

		if(!$Mind)
		{
			//如果找不到資料就回列表頁
			return redirect('/admin/mind');
		}

		$name = 'mind';
		$action = '修改';

		$binding = [
			'title' => ShareData::TITLE,
			'page' => $this->page,
			'name' => $name,
			'user' => $User,
			'mind' => $Mind,
			'action' => $action,
			'result' => '',
		];
		return view('admin.mind', $binding);
	}
}
?>