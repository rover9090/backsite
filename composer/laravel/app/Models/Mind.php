<?PHP
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mind extends Model {
    //資料表名稱
    protected $table = 'mind';

    //主鍵名稱
    protected $primaryKey = 'nId';

    //可變動欄位
    protected $fillable = [
        'nUid',
        'sContent',
        'nOnline',
    ];
}
?>