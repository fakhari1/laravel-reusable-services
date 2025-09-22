<?php

namespace Modules\Finance\Livewire\Invoice\Admin;

use App\Helpers\HelperClass\GeneralUse;
use Illuminate\Filesystem\Filesystem;
use Livewire\Component;
use Masmerise\Toaster\Toastable;
use Modules\Finance\Models\Detailed;
use Modules\Finance\Models\Factor;
use function App\Helpers\saveUser;

class UpdateInvoice extends Component
{
    use Toastable;
    public $item;
    public $services_id;
    public $num=1;
    public function mount($factor_id){
        if(auth('admin')->check() || auth()->user()->can( 'secretary')){

//            $this->item=Factor::where('id',$factor_id)->with('rows')->first();
        }else{
            $this->services_id=saveUser(null,null,'services_id_return');
//            $this->item=Factor::where('id',$factor_id)
//            ->with('customer')
//            ->whereHas('customer',function($q){
//                $q->where('services_id',$this->services_id);
//            }) ->with('rows')->first();

        }
        if(!$this->item){
            return abort(404);
        }else{
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
                        'detailed'=>$row->detailed->id,
                        'count'=>$row->count,
                        'price'=>$row->price,
                        'amount'=>$row->amount,
                    ]);
                }
            }else{
                array_push($this->form['rows'],[
                    'code'=>1,
                    'detailed'=>'0',
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
            if( $row['detailed'] == '0' || $row['detailed'] == '' ){
                $this->error('لطفا فیلد خدمات سطر '.($i+1).' را تکمل نمایید');
                return ;
            }
        }
        GeneralUse::edit_factor($this->form,$filesystem,$this->item,$this->num);

          //response
          session()->flash('successRequest', 'عملیات با موفقیت انجام گردید');
          if($this->item->order_id){
              return redirect()->to('/admin/dashboard/order/'.$this->item->order_id);
          }else{
              return redirect()->to('/admin/factor/show');
          }

    }
    public function render()
    {
        $user_id=saveUser(null,null,'services_id_return');
        $detailed=Detailed::where('user_id',$user_id)->with('kol')->whereHas('kol',function($q){
            $q->whereIn('name',['فروش کالا','درآمد خدمات','خرید کالا']);
        })->get();
        return view('livewire.admin.pages.factor.edit',['detailed'=>$detailed])->layoutData(['page_name' => 'edit_factor']);
    }
}
