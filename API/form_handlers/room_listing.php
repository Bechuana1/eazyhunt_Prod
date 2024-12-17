



<div class="card-body">
                                <div class="response col-12" id="apiResponse"></div>
                                <h2 class="text-center">Room listing</h2>
                                        <form method="post" enctype="multipart/form-data" id="roomsForm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="apartment_id">Select an Apartment:</label>
                                                        

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="type">Room type</label>
                                                        <select type="select" class="form-control"  name="room_type" style="text-align:center" id="room_type" required>
                                                            <option value="">Room Type</option>
                                                            <option value="Single">Single</option>
                                                            <option value="Bedsitter">Bedsitter</option>
                                                            <option value="Double">Double</option>
                                                            <option value="1 Bedroom">1 Bedroom</option>
                                                            <option value="2 Bedroom">2 Bedroom</option>
                                                            <option value="commercial">commercial</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="price">*Price</label>
                                                        <input type="number" class="form-control" id="price" placeholder="ksh 6,500" name="price" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="facilities1">more Facilities (room specific facilities)</label>
                                                        <input type="text" class="form-control" id="facilities1" placeholder="Wi-Fi, Water, Security..." name="facilities1">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="description">Description</label>
                                                        <input type="text" class="form-control" id="description" placeholder="Room number, Floor, e.t.c .." name="description">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="vacant">Vacant/Occupied</label>
                                                        <select class="form-control" id="vacant" name="vacant">
                                                            <option value="1">Vacant</option>
                                                            <option value="0">Occupied</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="wrapper text-center">
                                                  <div class="container mt-3">
                                                    <div class="upload-container">
                                                      <div class="border-container">
                                                        <div id="selected-images-container" class="row mt-3"></div>
                                                        <div class="icons fa-4x" id="preview-container">
                                                            <i class="fas fa-file-image d-none d-sm-inline" data-fa-transform="shrink-3 down-2 left-6 rotate--45"></i>
                                                            <i class="fas fa-file-alt" data-fa-transform="shrink-2 up-4"></i>
                                                            <i class="fas fa-file-pdf d-none d-sm-inline" data-fa-transform="shrink-3 down-2 right-6 rotate-45"></i>
                                                        </div>
                                                        <label for="file-upload" class="file-selector-button">
                                                          <p>
                                                            Drag and drop files here, or 
                                                            <a href="#" id="file-browser">
                                                              Browse
                                                              <input type="file" id="image" name="image[]" accept="image/*" multiple style="display:none;">
                                                            </a> your phone

                                                          </p>
                                                        </label>
                                                        <input type="file" id="file-upload" name="file[]" accept="image/*" multiple style="display:none;">
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                              
                                              <script>
                                                document.getElementById('file-browser').addEventListener('click', function() {
                                                  document.getElementById('image').click();
                                                });
                                                document.getElementById('image').addEventListener('change', function () {
                                                // Get the selected files
                                                var files = this.files;

                                                // Container to display selected images
                                                    var selectedImagesContainer = document.getElementById('selected-images-container');

                                                // Clear existing images
                                                selectedImagesContainer.innerHTML = '';

                                                // Display selected images horizontally
                                                for (var i = 0; i < files.length; i++) {
                                                    var file = files[i];
                                                    var reader = new FileReader();

                                                    reader.onload = function (e) {
                                                        var img = document.createElement('img');
                                                        img.src = e.target.result;
                                                        img.className = 'img-thumbnail m-2';
                                                        img.style.maxHeight = '100px';
                                                        img.style.maxWidth = '100px';

                                                        // Append the image directly to the container
                                                        selectedImagesContainer.appendChild(img);
                                                    };

                                                    reader.readAsDataURL(file);
                                                }

                                                // Hide the icon container
                                                var iconContainer = document.querySelector('.icons');
                                                if (iconContainer) {
                                                    iconContainer.style.display = 'none';
                                                }

                                            });
                                              </script>
                                              

                                            <div class="d-flex justify-content-end mr-5">
                                                <div class="d-flex justify-content-end mr-5">
                                                    <button class="btn btn-success">Add Room <i class="bi bi-send"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>