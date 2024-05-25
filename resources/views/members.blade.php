@extends('layout')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
<script>

    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('csv-file');
        const label = document.querySelector('.upload-csv-label');
        const button = document.querySelector('.upload-button');
        const fileNameDisplay = document.createElement('span');
        fileNameDisplay.className = 'file-name-display';
        label.appendChild(fileNameDisplay);

        input.addEventListener('change', function() {
            const fileName = input.files[0] ? input.files[0].name : '';
            fileNameDisplay.textContent = fileName ? ` - ${fileName}` : '';
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const startScannerButton = document.getElementById("startScannerButton");
        const removeScannerButton = document.getElementById("removeScannerButton");
        const videoElement = document.getElementById("video");
        const canvasElement = document.getElementById("canvas");

        let stream;

        startScannerButton.onclick = function() {
            navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
            .then(function(mediaStream) {
                stream = mediaStream;
                videoElement.srcObject = stream;
                videoElement.setAttribute("playsinline", true);
                videoElement.play();
                videoElement.style.display = "block";
                removeScannerButton.style.display = "block";
                startScanning(videoElement, canvasElement);
            })
            .catch(function(err) {
                console.error("Error accessing the camera.", err);
            });
        };

        removeScannerButton.onclick = function() {
            stopVideoStream();
            videoElement.style.display = "none";
            removeScannerButton.style.display = "none";
        };

        function startScanning(videoElement, canvasElement) {
            const canvas = canvasElement.getContext("2d");
            const qrCodeInterval = setInterval(function() {
                if (videoElement.readyState === videoElement.HAVE_ENOUGH_DATA) {
                    canvasElement.height = videoElement.videoHeight;
                    canvasElement.width = videoElement.videoWidth;
                    canvas.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
                    const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert",
                    });
                    if (code) {
                        clearInterval(qrCodeInterval);
                        alert("QR Code detected: " + code.data);
                        stopVideoStream();
                        videoElement.style.display = "none";
                        removeScannerButton.style.display = "none";
                    }
                }
            }, 100);
        }

        function stopVideoStream() {
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach(function(track) {
                    track.stop();
                });
            }
        }
    });

</script>

            
        <div class="sup-container2">

            <div class="sup-container3">
                <div class="csv-container3">
                    <a href="/members/csv-download" target="_blank" class="download-csv"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                      </svg>Download CSV</a>
        
                    <a href="/members/pdf-download" target="_blank" class="download-csv"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                      </svg>Download PDF</a>
                </div>

                <form action="{{ route('reservations.import.csv') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="csv_file">
                    <button type="submit" class="btn btn-primary">Import CSV</button>
                </form> 
            <div class="scanner">
                <div>
                    <button id="startScannerButton" class="scanner-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-qr-code-scan" viewBox="0 0 16 16">
                            <path d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5M.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5M4 4h1v1H4z"/>
                            <path d="M7 2H2v5h5zM3 3h3v3H3zm2 8H4v1h1z"/>
                            <path d="M7 9H2v5h5zm-4 1h3v3H3zm8-6h1v1h-1z"/>
                            <path d="M9 2h5v5H9zm1 1v3h3V3zM8 8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8zm2 2H9V9h1zm4 2h-1v1h-2v1h3zm-4 2v-1H8v1z"/>
                            <path d="M12 9h2V8h-2z"/>
                          </svg>
                    </button>

                    <button id="removeScannerButton" style="display:none;" class="remove-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                          </svg>
                    </button>
                </div>

                <div>
                    <video id="video" style="display:none; width: 100px; height: 100px;"></video>
                    <canvas id="canvas" style="display:none;"></canvas>
                </div>

            </div>
        </div>
        </div>


    <div> 
        <table class="members" >
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $mem)
                        <tr>
                            <td>{{ $mem->name}}</td>
                            <td>{{ $mem->description}}</td>
                            <td>{{ $mem->type->name}}</td>
                            <td>{{ $mem->price}}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('members.show', ['member' => $mem->id]) }}" class="show-button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                      </svg></a>
                                    {{-- <button class="delete-button" onclick="confirmDelete({{ $prod->id }})"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                      </svg></button> --}}
                                </div>
                            </td>   
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>

    
@endsection

<style>

.csv-container2 {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
}

.upload-csv {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.download-csv2 {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.import-csv {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.upload-csv-label {
  display: flex;
  align-items: center;
  cursor: pointer;
  color: #007bff;
}

.upload-csv-label svg {
  margin-right: 8px;
}

.upload-button {
  margin-top: 10px;
  padding: 10px 20px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.upload-button:hover {
  background-color: #0056b3;
}

.members {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid rgb(169, 167, 167);
        }

        .members th, .members td {
            padding: 8px;
            text-align: left;
            border: 1px solid rgb(207, 202, 202);
            background-color: white;
            font-size: 13px;
        }

        .members th {
            background-color: white;
            text-align: center;
        }

        .action-buttons {
            display: flex;
            border-radius: 9px;
            gap:5px;
            justify-content: space-around;
        }
        .sup-container2 {
            padding: 20px;
        }
        .sup-container3 {
            justify-content: space-between;
            display: flex;
            align-items: center;
        }
        .sup-create {
            display: flex;
            justify-content: space-between;
        }
        .csv-container svg {
            color: rgb(41, 34, 237);
        }
        .Sup-Container {
            background-color: rgb(92, 89, 89);
            padding: 5px;
            margin-top: 10px;
            border-radius: 5px;
        }

        .download-csv {
            display: flex; 
            margin-bottom: 5px;
            width: 100px;
            align-items: center;
            background-color: #fff;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #007bff;
            color: #007bff;
            font-weight: bold;
            font-size: 11px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .download-csv svg {
            margin-right: 8px;
        }
        .add-product {
            display: flex;
            gap: 5px;
            align-items: center;
            padding: 5px;
            border-radius: 3px;
            background-color: rgb(52, 145, 244);
            color: rgb(245, 240, 240);
        }
</style>