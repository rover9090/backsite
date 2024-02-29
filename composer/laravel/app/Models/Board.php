<?PHP
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Board extends Model {
	//資料表名稱
	protected $table = 'board';

	//主鍵名稱
	protected $primaryKey = 'nId';

	//可變動欄位
	protected $fillable = [
		'nUid',
		'nBoardId',
		'sEmail',
		'sPicture',
		'sContent',
		'nOnline',
	];

	# hasOne表示一個留言板(Board)資料只會對應一個使用者(User)資料,
	# 而用User的id欄位跟Board的user_id欄位相對應,
	public function User()
	{
		return $this->hasOne('App\Models\User', 'nId', 'nUid');
	}
}
?>
