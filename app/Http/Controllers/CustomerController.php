<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\SellingInvoice;
use App\Models\SellingInvoiceDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\notifikasi_pembelian;
use App\Models\Product;
use App\Models\User;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function booking() {
        $carts = Auth()->user()->cart;

        return view("user.detail-pesanan", [
            "carts"=> $carts,
        ]);
    }

    public function booking_detail(Request $request){
        if($request->resep_dokter != NULL){
            $validated_data = $request->validate([
                'nama' => ['required', 'string', 'min:5', 'max:255'],
                'nomor_telepon' => ['required','numeric', 'nullable', 'digits_between:10,14', 'starts_with:08'],
                'resep_dokter' => ['required', 'file', 'max: 5120' ,'mimes:pdf,doc,docx,png,jpeg,jpg'],
            ]);
        }else{
            $validated_data = $request->validate([
                'nama' => ['required', 'string', 'min:5', 'max:255'],
                'nomor_telepon' => ['required', 'numeric', 'nullable', 'digits_between:10,14', 'starts_with:08'],
            ]);
        }

        if($request->file('resep_dokter')){
            $validated_data['resep_dokter'] = $request->file('resep_dokter')->store('resep-dokter');
        }
        // dd(str_replace("resep-dokter/", "",$file));
        return view('user.pembayaran', [
            'nama' => $request->nama,
            'nomor_telepon'=> $request->nomor_telepon,
            'resep_dokter'=> $request->file('resep_dokter') ? str_replace("resep-dokter/", "",$validated_data['resep_dokter']) : NULL,
            'catatan' => $request->catatan ?? NULL,
            'totalHarga' => $request->total,
        ]);
    }
    public function pembayaran(Request $request){
        $status = false;

        $validated_data = $request->validate([
            'paymentMethod'=> ['required'],
            'buktiPembayaran' => ['required', 'file', 'max: 5120','mimes:pdf,doc,docx,png,jpeg,jpg'],
        ]);

        $produk_id = SellingInvoice::orderBy('invoice_code', 'desc')->pluck('invoice_code')->first();
        $number = intval(str_replace("INV-", "", $produk_id)) + 1;
        
        $validated_data['buktiPembayaran'] = $request->buktiPembayaran->store('bukti-pembayaran');

        $produks = auth()->user()->cart->all();

        try {
            DB::beginTransaction();
            $uuid = Str::uuid();

            // membuat invoice
            SellingInvoice::create([
                'selling_invoice_id'=> $uuid,
                'invoice_code' => 'INV-'. str_pad($number, 6, '0', STR_PAD_LEFT),
                'customer_id' => auth()->user()->user_id,
                'recipient_name' => $request->nama,
                'recipient_phone' => $request->nomor_telepon,
                'recipient_file' => $request->resep_dokter,
                'recipient_request'=> $request->catatan ?? "",
                'recipient_bank' => $request->paymentMethod,
                'recipient_payment'=> str_replace("bukti-pembayaran/", "",$validated_data['buktiPembayaran']),
                'order_date' => NOW(),
                'order_status' => $request->status,
            ]);
            // selesai membuat invoice

            foreach($produks as $produk){
                // memasukan product ke invoice_detail
                SellingInvoiceDetail::create([
                    'selling_detail_id' => Str::uuid(),
                    'selling_invoice_id' => $uuid,
                    'product_name' => $produk->product->product_name,
                    'product_sell_price' => $produk->product->detail()->orderBy('product_expired')->first()->product_sell_price,
                    'quantity' => $produk->quantity,
                ]);
                // selesai memasukan product ke invoice_detail

                $stock = $produk->product->detail()->orderBy('product_expired')->first()->product_stock - $produk->quantity;

                // mengurangi stock
                $produk->product->detail()->orderBy('product_expired')->first()->update([
                    'product_stock' => $stock,
                ]);
                // akhir mengurangi stock

                if($stock == 0){
                    if($produk->product->detail()->count() <= 1){
                        $produk->product->update([
                            'product_status' => 'tidak aktif',
                        ]);
                    }else{
                        $produk->product->detail()->orderBy('product_expired')->first()->delete();
                    }
                }
            }
            
            foreach($produks as $produk){
                // menghapus product dari cart
                $produk->delete();
                // selesai menghapus product dari cart
            }

            $alamatEmails = User::where('role', 'cashier')->get();

            foreach($alamatEmails as $alamatEmail){
                Mail::to($alamatEmail)->send(new notifikasi_pembelian());
            }

            $status = true;

            DB::commit();
        } catch (\Exception $e) {
            $status = false;

            DB::rollback();
            throw $e;
        }
        
        if ($status){
            return redirect('/')->with('status', "pembayaran-berhasil")->with('invoice_code', 'INV-'. str_pad($number, 6, '0', STR_PAD_LEFT))->with('customer_name', $request->nama);
        }else{
            return redirect('/')->with('status', "pembayaran-gagal");
        }
    }

    public function informasi_pembayaran(Request $request){
        return view('user.show-image',[
            'title' => 'Bukti Pembayaran',
            'root' => 'bukti-pembayaran',
            'file'=> $request->file,
            'id'=> $request->id,
        ]);
    }

    public function resep_dokter(Request $request){
        return view('user.show-image',[
            'title' => 'Resep Dokter',
            'root' => 'resep-dokter',
            'file'=> $request->file,
            'id'=> $request->id,
        ]);
    }

    public function refund(Request $request){
        return view('user.show-image',[
            'title' => 'Refund',
            'root' => 'refund',
            'file'=> $request->file,
            'id'=> $request->id,
        ]);
    }

    public function cetak_struk(Request $request) {
        $invoice = SellingInvoice::where('selling_invoice_id', $request->id)->first();
        // dd($invoice);
        return view('user.invoice', [
            'invoice'=> $invoice,
        ]);
    }
}
