<div class="flex flex-col items-center mb-8">
    <div class="w-[80vw] mt-8 flex flex-col gap-8 ">
        <p class="font-TripBold text-4xl">Banyak Dicari</p>    

        <div class="flex justify-evenly relative">
            <div class="flex flex-wrap justify-center gap-4">
                @if ($products_best_seller->first() != NULL)
                @foreach ($products_best_seller as $product)
                <div class="h-full w-[230px] shadow-md border-2 shadow-semiBlack rounded-lg p-4 flex flex-col bg-white">
                    <a href="" >
                        <div class="px-2 w-full">
                            <p class="font-semibold text-lg namaObat flex whitespace-normal break-words">{{ $product->first()->product_name }}</p>
                        </div>

                        <center class="relative">
                            @if ($product->first()->detail->product_type == "resep dokter")
                            <span class="bg-red-500 text-white font-semibold px-2 py-1 text-sm rounded-md absolute top-1 left-2">Resep</span>
                            @endif

                            <img src="{{ asset('img/obat1.jpg') }}" width="150px" alt="" draggable="false">    
                        </center>
                    </a>

                    <div class="flex justify-between items-center">
                        <div class="px-2 flex flex-col justify-center w-[80%] whitespace-normal break-words">
                            <p><span class="font-TripBold text-secondaryColor">Rp. {{ number_format($product->first()->product_sell_price, 0,
                                    ',', '.') }}</span> / </br>{{ $product->first()->detail->unit->unit }}</span></p>
                            <p class="font-semibold">Stok: {{ $product->first()->product_stock }}</p>
                        </div>
                        
                        <div class="w-[20%] h-full">
                            @if ($product->first()->product_stock == 0)
                            @else
                            <button type="submit" class="bg-mainColor h-[40px] w-[40px] rounded-full text-white cursor-pointer flex justify-center items-center">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <p class="text-2xl">Belum Memiliki Product!</p>  
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    const obatElement = document.getElementsByClassName("namaObat");

    for (let i = 0; i < obatElement.length; i++) {
    const obatText = obatElement[i].textContent;

    if (obatText.length > 18) {
        obatElement[i].textContent = obatText.slice(0, 16) + "...";
    }
    }
</script>