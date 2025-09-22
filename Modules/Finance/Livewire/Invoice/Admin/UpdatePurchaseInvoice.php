<?php

namespace Modules\Finance\Livewire\Invoice\Admin;

use App\Helpers\HelperClass\GeneralUse;
use DB;
use Illuminate\Filesystem\Filesystem;
use Livewire\Component;
use Masmerise\Toaster\Toastable;
use Modules\Finance\Models\Detailed;
use Modules\Finance\Models\Factor;
use Modules\Warehouse\Models\Product;
use function App\Helpers\saveUser;

class UpdatePurchaseInvoice extends Component
{
    use Toastable;
    public $services_id;
    public $item;
    public $num=1;
    protected $listeners = ['refreshData' => '$refresh'];
    public function mount($factor_id){
        $this->services_id=saveUser(null,null,'services_id_return');
        if(auth('admin')->check() || auth()->user()->can( 'secretary')){

//            $this->item=Factor::where('id',$factor_id)->first();
        }else{
//            $this->item=Factor::where('id',$factor_id)
//            ->when(auth()->user()->can( 'services'),function($q){
//                $q->with('customer')->where(function($q){
//                    $q->whereHas('customer',function($q){
//                        $q->where('services_id',auth()->user()->id);
//                    })->orWhere('services_id',auth()->user()->id);
//                });
//            })->first();

        }
        if($this->item){
            $this->form=[
                'number'=>$this->item->number,
                'date'=>$this->item->date,
                'tel_seller'=>$this->item->tel_seller,
                'seller'=>$this->item->seller,
                'buyer'=>$this->item->buyer,
                'tel_buyer'=>$this->item->tel_buyer,
                'total'=>number_format($this->item->total),
                'discount'=>number_format($this->item->discount),
                'total_price'=>number_format($this->item->total_price),
                'description'=>$this->item->description,
                'rows'=>[]
            ];
            if(0 < count($this->item->rows)){
                $this->num=count($this->item->rows);
                foreach ($this->item->rows as  $row) {
                    array_push($this->form['rows'],[
                        'code'=>$row->code,
                        'detailed'=>$row->detailed_id,
                        'product_id'=>$row->product_id,
                        'count'=>$row->count,
                        'price'=>$row->price,
                        'amount'=>$row->amount,
                    ]);
                }
            }else{
                array_push($this->form['rows'],[
                    'code'=>1,
                    'detailed'=>'0',
                    'product_id'=>'0',
                    'count'=>0,
                    'price'=>0,
                    'amount'=>0,
               ]);

            }
        }
    }

    public $form=[];
    public static function replaceComma($str){
        $result=str_replace( ',', '', $str );
        return $result;
    }
    public function handelRow($type=null)
    {
        if($type == 'increment'){
            $this->num=$this->num+1;
            array_push($this->form['rows'],[
                'code'=>$this->num,
                'detailed'=>'0',
                'count'=>0,
                'price'=>0,
                'amount'=>0,
            ]);
        }elseif(1 < $this->num){
            $this->num=$this->num-1;
            unset($this->form['rows'][$this->num]);
        }
        $this->processAll();
    }
    public function processAll()
    {
        $total=0;
        foreach ($this->form['rows'] as  $row) {
            $total=$total + (float)$row['amount'];
        }
        $this->form['total']=number_format((float)$total);
        $this->form['total_price']=number_format((float)$this->replaceComma($this->form['total']) - (float)$this->replaceComma($this->form['discount']));
    }
    public function updatedFormTotal()
    {
        $this->form['total_price']=number_format((float)$this->replaceComma($this->form['total']) - (float)$this->replaceComma($this->form['discount']));
    }
    public function updatedFormDiscount()
    {
        $this->form['total_price']=number_format((float)$this->replaceComma($this->form['total']) - (float)$this->replaceComma($this->form['discount']));
    }
    public function handelRows($val,$key,$num)
    {
        if($key == 'detailed'){
            $this->form['rows'][$num]['price']=Detailed::where('id',$val)->first()->price;
            $this->form['rows'][$num]['amount']=(float)$this->form['rows'][$num]['count'] * (float)$this->form['rows'][$num]['price'];
        }
        if($key == 'count'){
            $this->form['rows'][$num]['amount']=(float)$this->form['rows'][$num]['price'] * (float)$this->form['rows'][$num][$key];
        }
        if($key == 'price'){
            $this->form['rows'][$num]['amount']=(float)$this->form['rows'][$num]['count'] * (float)$this->form['rows'][$num][$key];
        }
        $this->processAll();
    }
    public function submit(Filesystem $filesystem)
    {

        foreach ($this->form['rows'] as  $i=>$row) {
            if( $row['detailed'] == '0'){
                $this->error('لطفا فیلد خرید سطر '.($i+1).' را تکمیل نمایید');
                return ;
            }
            if( $row['product_id'] == '0'){
                $this->error('لطفا فیلد محصول سطر '.($i+1).' را تکمیل نمایید');
                return ;
            }
        }
        GeneralUse::edit_buy_factor($this->form,$filesystem,$this->item,$this->num);
        //response
        \Redirect::to('/admin/factor/show')->success('عملیات با موفقیت انجام شد');

    }
    public function render()
    {
        $detailed=Detailed::where('user_id', $this->services_id)->with('kol')->whereHas('kol',function($q){
            $q->whereIn('name',['خرید کالا']);
        })->get();
        $products=Product::when(auth()->user()->can( 'employee'),function($q){
            $q->where('user_id',auth()->user()->parent);
        },function($q){
            $q->where('user_id',auth()->user()->id);
        })->orderBy('id','desc')->get();
        return view('livewire.admin.pages.factor.buy.edit',['detailed'=>$detailed,'products'=>$products])->layoutData(['page_name' => 'add_factor']);
    }
}
