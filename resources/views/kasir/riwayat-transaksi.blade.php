<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kasir | Riwayat Transaksi</title>
    @vite('resources/css/app.css')

    {{-- FONT AWESOME --}}
    <script src="https://kit.fontawesome.com/e87c4faa10.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/1fc4ea1c6a.js" crossorigin="anonymous"></script>

    {{-- DATATABLES --}}
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />

</head>
<body class="font-Inter relative">
    @include("kasir.components.sidebar")
    <main class="p-10 font-Inter bg-plat min-h-[100vh] h-full" id="mainContent">
        @include("kasir.components.navbar")

        <div class="flex flex-col gap-8 mt-10">
            <p class="text-3xl font-bold">Riwayat Transaksi</p>

            <div class="bg-white rounded-lg p-4 shadow-md">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nomor Invoice</th>
                            <th>Nama Penerima</th>
                            <th>Waktu Pemesanan</th>
                            <th>Total Harga</th>
                            <th>Status Pesanan</th>
                            <th>Detail Pesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < 6; $i++)               
                        <tr>
                            <td>{{$i + 1}}</td>
                            <td>
                                <span class="font-bold">INV-1234{{$i}}</span>
                            </td>
                            <td>Nama Penerima Nama Penerima  {{$i + 1}}</td>
                            <td>24 Desember 2023</td>
                            <td>RP 650.000</td>
                            <td>
                                <div class="w-full flex gap-2 items-center">
                                    {{-- GREEN = BERHASIL, YELLOW = REFUND, RED = GAGAL --}}
                                    <i class="text-green-600 fa-solid fa-circle"></i>
                                    <i class="text-yellow-600 fa-solid fa-circle"></i>
                                    <i class="text-red-600 fa-solid fa-circle"></i>
                                    <p class="font-bold">Berhasil</p>
                                </div>
                            </td>
                            <td>
                                <div class="flex justify-center w-full">
                                    <button class="border-2 border-secondaryColor rounded-md hover:bg-transparent hover:text-secondaryColor font-bold px-4 py-1 bg-secondaryColor text-white duration-300 transition-colors ease-in-out" type="button" onclick="toggleDetail()">Lihat</button>
                                </div>

                                {{-- MODAL DETAIL RIWAYAT TRANSAKSI START --}}
                                <div class="absolute w-full h-screen top-0 left-0 flex justify-center items-center backdrop-brightness-75 z-10 hidden" id="detailModal">
                                    <div class="w-[70%] h-fit max-h-full bg-white rounded-md shadow-md p-8 flex flex-col gap-6 overflow-auto">
                                        <div class="flex justify-between items-center">
                                            <button onclick="toggleDetail()" type="button" class="bg-mainColor py-1 px-4 text-white font-semibold rounded-md">
                                                <i class="fa-solid fa-arrow-left"></i>
                                                Kembali
                                            </button>
    
                                            <p class="font-bold">INV-12345</p>
    
                                            <div class="flex gap-2 items-center">
                                                {{-- GREEN = BERHASIL, YELLOW = REFUND, RED = GAGAL --}}
                                                <i class="text-green-600 fa-solid fa-circle"></i>
                                                <i class="text-yellow-600 fa-solid fa-circle"></i>
                                                <i class="text-red-600 fa-solid fa-circle"></i>
                                                <p class="font-bold">Berhasil</p>
                                            </div>
                                        </div>
    
                                        <div class="px-8 py-2 w-[100%] flex justify-between">
                                            <div class="w-[70%]">
                                                <div class="flex flex-col gap-8">
                                                    <table class="w-full">
                                                        <tr class="border-2 border-b-mainColor border-transparent text-mainColor font-bold w-[100%]">
                                                            <td class="w-[10%] pb-2 text-center">No</td>
                                                            <td class="w-[30%] pb-2">Nama</td>
                                                            <td class="w-[10%] pb-2 text-center">Jumlah</td>
                                                            <td class="w-[25%] pb-2 text-center">Harga</td>
                                                            <td class="w-[25%] pb-2">Total</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="py-2 text-center">1</td>
                                                            <td class="py-2">Paracetamol 200 kg</td>
                                                            <td class="py-2 text-center">4</td>
                                                            <td class="py-2 text-center">Rp 5.000</td>
                                                            <td class="py-2">Rp 20.000</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="py-2 text-center">2</td>
                                                            <td class="py-2">Panadol 200 mg</td>
                                                            <td class="py-2 text-center">3</td>
                                                            <td class="py-2 text-center">Rp 2.500</td>
                                                            <td class="py-2">Rp 7.500</td>
                                                        </tr>
                                                    </table>
                                                </div>
    
                                                <div class="flex flex-col gap-2 py-2">
                                                    <hr class="border-2 border-transparent border-b-mainColor">
                                                    <div class="flex font-bold gap-2">
                                                        <p class="w-28">Total Harga</p>
                                                        <p>:</p>
                                                        <p class="text-secondaryColor">Rp 140.000</p>
                                                    </div>
                                                    <div class="flex font-bold gap-2">
                                                        <p class="w-28">Kasir</p>
                                                        <p>:</p>
                                                        <p class="text-mainColor">Go Youn Jung</p>
                                                    </div>
                                                    {{-- ALASAN GAGAL KALAU ADA --}}
                                                    <div class="flex font-bold gap-2">
                                                        <p class="w-28">Alasan Gagal</p>                                
                                                        <p>:</p>
                                                        <p class="text-mainColor">Ngerjain Tubes</p>
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="w-[25%]">
                                                <p class="text-center font-bold text-mainColor pb-2">Keterangan</p>
                                                <hr class="border-2 border-transparent border-b-mainColor">
                                                <div class="py-2">
                                                    <p class="font-bold">Pelanggan :</p>
                                                    <p>Bobby The Cat</p>
                                                    <p class="font-bold">Nomor HP :</p>
                                                    <p>0812-1234-1234</p>
                                                    <p class="font-bold">Tanggal Pengambilan :</p>
                                                    <p>25 Oktober 2023 10:15:28</p>
                                                    <p class="font-bold">Metode Pembayaran :</p>
                                                    <p>BCA</p>
                                                    <p class="font-bold">Bukti Pembayaran :</p>
                                                    <a href="/cashier/img" target="_blank" class="text-blue-600 underline">hehehe.jpg</a>
                                                    <p class="font-bold">Catatan :</p>
                                                    <p>Kapan Tubes Selesai</p>
                                                </div>
                                            </div>
                                        </div>
    
                                    </div>
                                </div>
                                {{-- MODAL DETAIL RIWAYAT TRANSAKSI END --}}
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    {{-- DATATABLES SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>

    <script>
        const toggleDetail = () => {
            const modal = document.getElementById('detailModal');

            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden')
                document.body.classList.add('h-[100vh]')
            } else {
                modal.classList.add('hidden')
                document.body.classList.remove('h-[100vh]')
            }
        }
    </script>
</body>
</html>