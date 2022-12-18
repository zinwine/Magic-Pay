@extends('frontend.layouts.app')
@section('page-name', 'Scan & Pay')
@section('content')
<div class="scan_and_pay">

    <div class="card mb-3 my-card">
        <div class="card-body text-center">
            @if (session('failed'))
                    <div class="text-center alert alert-danger fade show" role="alert">
                        {{session('failed')}}
                      </div>                    
                @endif
            <div class="text-center">
                <img src="{{asset('img/scan_and_pay.png')}}" alt="">
            </div>
            <p class="mb-3">Click buttom to Scan</p>
            <button type="button" class="btn btn_theme btn-sm" data-toggle="modal" data-target="#scanner">Scan</button>
            <!-- Modal -->
            <div class="modal fade" id="scanner" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Scan & Pay</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <video id="qr_scanner" width="100%" height="250px"></video>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
</div>
@endsection
@section('script')
    <script src="{{asset('frontend/js/instascan.min 2.js')}}"></script>
    <script>
        $(document).ready(function(){
            
            
            let scanner = new Instascan.Scanner({ video: document.getElementById('qr_scanner') });

            scanner.addListener('scan', function (content) {
                if(content){
                    scanner.stop();
                    $('#scanner').hide();

                    var to_phone = content;
                    window.location.replace(`scan_and_pay_form?to_phone=${to_phone}`);
                }
                console.log(content);

            });

            $('#scanner').on('show.bs.modal', function (e) {
                Instascan.Camera.getCameras().then(function (cameras) {
                    if (cameras.length > 0) {
                        scanner.start(cameras[0]);
                    } else {
                        alert('Sorry! Back Camera not found.')
                    }
                }).catch(function (e) {
                    console.error(e);
                });
                
                })

            $('#scanner').on('hidden.bs.modal', function (e) {
                scanner.stop();
            })
        })
    </script>
@endsection
