<div class="w-11/12 pt-10 mx-auto mt-10 " wire:poll>
    {{--    @include('components.layouts.general.loading')--}}
    <section class="w-full " dir="rtl">

        <div class="flex items-center justify-between py-3 mb-10 text-center border-b-2 border-main-700" wire:ignore>
            <div class="w-3/12"></div>
            <div class="w-6/12">
                <h2 class="text-lg font-thin text-main-800 xl:font-bold">مشاهده فاکتور ها</h2>
            </div>
            <div class="w-3/12">
                @if (auth()->user()->can( 'services') || auth()->user()->can( 'employee'))
                    <a href="/admin/factor/buy/add "
                       wire:navigate
                       class="w-full block py-2 text-xs  text-center text-white rounded bg-main-800 hover:text-white hover:bg-main-700 focus:outline-none">
                        صدور فاکتور خرید
                    </a>
                @endif
            </div>
        </div>
        @if (session()->has('successRequest'))
            <div class="my-3">
                <livewire:general.success-validate :message="session('successRequest')"/>
            </div>
        @endif

        <div>
            <!------------ Filter ---------->
            <div class="my-2">
                <div x-data="{open:false}">
                    <button type="button"
                            class="inline-flex w justify-center   px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50  "
                            x-on:click="open = !open">
                        فیلتر
                        <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                    </button>
                    <div x-show="open "
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class=" mt-3 w-full   md:flex md:space-y-0 md:space-x-2">
                        <div class=" space-y-2 lg:space-y-0 lg:flex justify-start items-center">
                            <input type="text" wire:model.live='order_id' placeholder="کد سفارش... "
                                   class="block w-full  ml-2 border-gray-300 rounded-md shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5  ">
                            <div class="w-full">
                                <livewire:general.date-time-picker :js="'true'" format="YYYY-MM-DD" min="vue"
                                                                   :vModel="'date'" :type_show="'range'" :type="'date'"
                                                                   :methode="'set_date'">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!------------ Table ---------->
            <div class=" w-full">
                <div class="block  shadow-md rounded-t-xl  overflow-x-auto">
                    <table class="w-full   text-left  overflow-hidden">
                        <thead>
                        <tr class="text-gray-900 bg-gray-100  ">

                            <th class="truncate py-2 text-center text-sm font-light min-w-[4rem] px-1  ">
                                #
                            </th>
                            <th class=" truncate py-2 text-center text-sm font-light min-w-[8rem] px-1   border-r border-gray-300 ">
                                شماره فاکتور
                            </th>
                            <th class=" truncate py-2 text-center text-sm font-light min-w-[8rem] px-1   border-r border-gray-300 ">
                                نوع فاکتور
                            </th>
                            <th class="truncate  py-2 text-center text-sm font-light min-w-[8rem] px-1   border-r border-gray-300 ">
                                تاریخ
                            </th>
                            <th class=" truncate  py-2 text-center text-sm font-light min-w-[8rem] px-1  border-r border-gray-300">
                                فروشنده
                            </th>
                            <th class="truncate  py-2 text-center text-sm font-light min-w-[8rem] px-1  border-r border-gray-300">
                                خریدار
                            </th>
                            <th class="truncate  py-2 text-center text-sm font-light min-w-[8rem] px-1 border-r border-gray-300 ">
                                جمع کل فاکتور
                            </th>
                            <th class="truncate  py-2 text-center text-sm font-light min-w-[8rem] px-1  border-r border-gray-300">
                                تخفیف
                            </th>
                            <th class="truncate  py-2 text-center text-sm font-light min-w-[8rem] px-1  border-r border-gray-300">
                                جمع کل
                            </th>
                            <th class=" truncate py-2 text-center text-sm font-light min-w-[8rem] px-1 border-r border-gray-300 ">
                                جزییات فاکتور
                            </th>
                            <th class=" truncate py-2 text-center text-sm font-light min-w-[8rem] px-1  border-r border-gray-300">
                                عملیات
                            </th>
                            <th class=" truncate py-2 text-center text-sm font-light min-w-[8rem] px-1 border-r border-gray-300 ">
                                جزییات سفارش
                            </th>
                            <th class="truncate  py-2 text-center text-sm font-light min-w-[8rem] px-1 border-r border-gray-300 ">
                                تاریخ ایجاد
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($result as $row)
                            <tr wire:key='{{ $row->id }}'
                                class="w-full font-light text-gray-800  whitespace-no-wrap bg-white border border-b ">
                                <td class="truncate px-2 py-2 text-sm text-center font-light ">
                                    {{ $row->id }}
                                </td>
                                <td class="truncate px-2 py-2 text-sm text-center font-light  border-r border-gray-300">
                                    @if($row->number)
                                        {{ $row->number }}
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td class="truncate px-2 py-2 text-sm text-center font-light  border-r border-gray-300">
                                    @if($row->type == 'sell')
                                        <span class="px-3 py-1 rounded-md bg-green-300">
                                            فروش
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-md bg-red-300">
                                            خرید
                                        </span>
                                    @endif
                                </td>
                                <td class="truncate px-2 py-2 text-sm text-center font-light  border-r border-gray-300">
                                    @if($row->date)
                                        {{ $row->date }}
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td class="truncate px-2 py-2 text-sm text-center font-light  border-r border-gray-300">
                                    @if($row->seller)
                                        {{ $row->seller }}
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td class="truncate px-2 py-2 text-sm text-center font-light  border-r border-gray-300">
                                    @if($row->buyer)
                                        {{ $row->buyer }}
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td class="truncate px-2 py-2 text-sm text-center font-light  border-r border-gray-300">
                                    @include('tables.cells.number',['price'=>$row->total])
                                </td>
                                <td class="truncate px-2 py-2 text-sm text-center font-light  border-r border-gray-300">
                                    @include('tables.cells.number',['price'=>$row->discount])
                                </td>
                                <td class="truncate px-2 py-2 text-sm text-center font-light  border-r border-gray-300">
                                    @include('tables.cells.number',['price'=>$row->total_price])
                                </td>
                                <td class="truncate px-2 py-2 text-sm text-center font-light  border-r border-gray-300">
                                    @include('tables.cells.actionsB',['link'=>$row->pdf,'name'=>"مشاهده "])
                                </td>
                                <td class="px-2 py-2 text-sm text-center font-light  border-r border-gray-300">
                                    @if($row->type == 'sell')
                                        @include('tables.cells.actionsB',['link'=> '/admin/factor/edit/'.$row->id,'name'=>"مشاهده ",'color'=>'bg-red-800  hover:bg-red-700'])
                                    @else
                                        @include('tables.cells.actionsB',['link'=> '/admin/factor/buy/edit/'.$row->id,'name'=>"مشاهده ",'color'=>'bg-red-800  hover:bg-red-700'])
                                    @endif
                                </td>
                                <td class="px-2 py-2 text-sm text-center font-light  border-r border-gray-300">
                                    @if($row->type == 'sell')
                                        @include('tables.cells.actionsB',['link'=> '/admin/dashboard/order/'.$row->order_id,'name'=>"مشاهده ",'color'=>'bg-main-800  hover:bg-main-700'])
                                    @else
                                        --
                                    @endif
                                </td>
                                <td class="truncate px-2 py-2 text-sm text-center font-light  border-r border-gray-300">
                                    {{ \App\Helpers\dateConvert($row->created_at,'formatS') }}
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="mt-2">
                    {{ $result->links() }}
                </div>
            </div>
        </div>


    </section>
</div>
@section('scripts')
    <script src="{{ asset('/js/persian_date/vue.js') }}"></script>
    <script src="{{ asset('/js/persian_date/moment.js') }}"></script>
    <script src="{{ asset('/js/persian_date/moment-jalaali.js') }}"></script>
    <script src="{{ asset('/js/persian_date/vue-persian-datetime-picker-browser.js') }}"></script>
@endsection
