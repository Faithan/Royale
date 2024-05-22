<!DOCTYPE html>
<html>

<head>
    <title>Form Example</title>
    <style>
    #colorList li {
        display: inline-block;
        padding: 5px;
        margin: 5px;
        border-radius: 5px;
        color: white;
    }
</style>
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">

        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name">
        </div>

        <div class="input-fields">

            <label for="color">Colors:</label><br>

            <div class="select-colors">
                <div><input type="text" id="colorInput" placeholder="Enter color"></div>
                <div><input type="color" id="colorPicker"></div>
                <div><button type="button" id="addButton">Add</button></div>
            </div>

            <ul id="colorList"></ul>
            
            <input type="text" name="colors[]" id="colorsInput" value="">
        </div>

        <div class="input-fields">

            <label for="size">Sizes:</label><br>
            <input type="text" name="size" id="sizeInput" placeholder="Enter size and press Enter">
            <ul id="sizeList"></ul>

            <input type="hidden" name="sizes[]" id="sizesInput" value="">

        </div>

        <input type="file" name="photo">

        <div><button type="submit" name="submit">Submit</button></div>
    </form>
</body>

</html>