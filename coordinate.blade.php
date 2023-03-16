<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css"
        integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->

    <style>
        body {
            background-color: lightblue;
        }

        canvas {
            border: 1px solid black;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
    </script>
    {{-- <script>
        $(document).on('mousedown', 'select option', function(e) {
            var self = $(this);
            var selectedValue = self.val();
            if ($('#' + selectedValue).length) {
                return;
            }
            var draggableDiv = $('<div/>').prop('id', selectedValue).css({
                position: 'absolute',
                left: e.pageX,
                top: e.pageY,
                width: 100,
                height: self.height(),
                cursor: 'default',
                background: '#ff0',
                opacity: 1
            }).text(self.val());
            $('form').append(draggableDiv);

            draggableDiv.draggable({
                revert: false,
            });
        });

        //
        $(function() {
            debugger

            var positions = [];
            $("form-control").draggable();
            $("canvas").droppable({

                drop: function(event, ui) {
                    debugger
                    var id = ui.draggable.attr("id");
                    // alert(id);
                    var offset = $(this).offset();
                    var xPos = ui.offset.left - offset.left;
                    var yPos = ui.offset.top - offset.top;
                    //var Input=selectedValue;
                    positions[id] = {
                        x: xPos,
                        y: yPos
                    };
                    //alert(xPos, yPos);
                    $("#Input").text(id);
                    //$("#coordinat").text("X: " + xPos + ", Y: " + yPos);
                    $("#coordinat").text("FieldName: " + id + ", X: " + xPos + ", Y: " + yPos).append(
                        "Vamshi");
                    $("#coordinates").text(xPos);
                    $("#ycoordinates").text(yPos);

                }
            });
        });
    </script> --}}

</head>

<body>
    {{-- newscript --}}
    <script>
        $(document).ready(function() {
            //var positions = {};
            var positions = [];
            var inputs = [];
            var data = [];

            $(document).on('mousedown', 'select option', function(e) {
                var self = $(this);
                var selectedValue = self.val();
                if ($('#' + selectedValue).length) {
                    return;
                }
                var draggableDiv = $('<div/>').prop('id', selectedValue).css({
                    position: 'absolute',
                    left: e.pageX,
                    top: e.pageY,
                    width: 100,
                    height: self.height(),
                    cursor: 'default',
                    background: '#ff0',
                    opacity: 1
                }).text(self.val());

                $('form').append(draggableDiv);

                draggableDiv.draggable({
                    revert: false,
                });

                /**$("canvas").droppable({
                    drop: function(event, ui) {
                        var id = ui.draggable.attr("id");
                        var offset = $(this).offset();
                        var xPos = ui.offset.left - offset.left;
                        var yPos = ui.offset.top - offset.top;
                        inputs.push(selectedValue + "," + xPos + "," + yPos);
                        //positions.push(xPos, yPos);

                        data.name = selectedValue;
                        data.xPos = xPos;
                        data.yPos = yPos;
                        //inputs.push(data)
                        //positions.push(xPos, yPos);
                        console.log("vamshi====", inputs);
                        $("#Input").html(data);
                    }
                });**/
                //new code
                $("canvas").droppable({
                    drop: function(event, ui) {
                        var id = ui.draggable.attr("id");
                        var offset = $(this).offset();
                        var xPos = ui.offset.left - offset.left;
                        var yPos = ui.offset.top - offset.top;
                         
                        // Find the index of the selected value in the inputs array
                        var index = inputs.findIndex(function(item) {
                            return item.startsWith(selectedValue);
                        });
                        //alert(index);

                        // If the selected value already exists in the array, update its position
                        if (index !== -1) {
                            var data = inputs[index].split(',');
                            data[1] = xPos;
                            data[2] = yPos;
                            inputs[index] = data.join(',');
                            //alert(index);
                        }
                        // If the selected value doesn't exist in the array, add it
                        else {
                            inputs.push(selectedValue + "," + xPos + "," + yPos);
                        }

                        // Display all selected options and their positions in the #Input element
                        var inputText = "";
                        for (var i = 0; i < inputs.length; i++) {
                            var data = inputs[i].split(',');
                            inputText += data[0] + ": (" + data[1] + ", " + data[2] + ")\n";
                        }
                        $("#Input").text(inputText);
                        //alert(inputText);
                        //positions.push(xPos, yPos);

                        //data.name = selectedValue;
                        //data.xPos = xPos;
                        //data.yPos = yPos;
                        //inputs.push(data)
                        //positions.push(xPos, yPos);
                        //console.log("vamshi====", inputtext);
                       //$("#Input").html(data);
                    }
                });

            });
        });
    </script>
    <title>Image Coordinates</title>
    <div class="container-fluid">
        <form id="myForm">
            @csrf
            <input type="hidden" id="custId" name="cusId" value={{ $document->id }}>
            <div class="col-md-4 " style="margin-left:605px">
                <label for="input">Input value:</label>
                <select name="fname" id='input' class="form-control" multiple='multiple'>
                    <option value="0">Please select ...</option>
                    <option value="HallticketNumber">HallticketNumber</option>
                    <option value="Name">Name</option>
                    <option value="FatherName">FatherName</option>
                    <option value="Age">Age</option>
                    <option value="DOB">DOB</option>
                    <option value="email">email</option>
                    <option value="Petlocation">Petlocation</option>
                    <option value="Gender">Gender</option>
                </select>
            </div>

            <center>
                <canvas id="canvas"></canvas>
                <p id="Input" style="font-family:verdana;font-size:160%;"></p>
                <p id="coordinat" name="fool" style="font-family:verdana;font-size:160%;"></p><br>
                <p hidden id="coordinates"></p>
                <p hidden id="ycoordinates"></p><br>
                <button type="submit">Submit</button>
            </center>
            <input type="hidden" id="Input2" name='name2'>
            <input type="hidden"id="Xco" name="X">
            <input type="hidden"id="Yco" name="Y">

        </form>

    </div>


</body>

</html>

<script>
    // Get canvas element
    var canvas = document.getElementById('canvas');
    canvas.width = 1121;
    canvas.height = 789;
    var ctx = canvas.getContext('2d');

    // Create image object
    var img = new Image();
    img.src = '{{ url("images/$document->certificate_path") }}';

    // Draw image on canvas
    img.onload = function() {
        ctx.drawImage(img, 0, 0);
    }
</script>

{{-- //ajax --}}
<script>
    jQuery('#myForm').submit(function(e) {
        // var id = $('#input :selected').toArray().map(item => item.val);
        var name = document.getElementById("Input").innerHTML;
        var latitude = document.getElementById("coordinates").innerHTML;
        var longitude = document.getElementById("ycoordinates").innerHTML;
        document.getElementById("Input2").value = name;
        //alert(name);
        //coordinates
        document.getElementById("Xco").value = latitude;
        document.getElementById("Yco").value = longitude;
        //alert(longitude);
        e.preventDefault();
        jQuery.ajax({
            url: "{{ url('run') }}",
            data: jQuery('#myForm').serialize(),
            //data:{name:name},
            type: 'post',
            success: function(result) {
                alert(result);
                //console.log(result)
            },
            error: function(xhr, status, error) {
                alert("Error occured", error);
            }
        })
    })
</script>
