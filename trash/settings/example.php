<?php
include ('dbconnect.php');
session_start();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $colors = $_POST['colors']; // Retrieve colors directly
    $sizes = $_POST['sizes']; // Retrieve sizes directly

    // Convert colors string to an array
    $colorsArray = explode(' ', $colors);

    // Validate each color in the array
    $validColors = array_map('validateColor', $colorsArray);

    // Serialize the valid colors array
    $serializedColors = serialize($validColors);

    // Insert the form data into the database
    $sql = "INSERT INTO example (id, name, colors, sizes, photo) VALUES ('','$name', '$serializedColors', '$sizes','')";
    if (mysqli_query($con, $sql)) {
        echo "Data saved successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }
}

// Function to validate color format
function validateColor($color)
{
    // Check if the color is a valid hex color code
    if (preg_match('/^#[a-f0-9]{6}$/i', $color)) {
        return $color;
    } else {
        // Default to white if not a valid hex color
        return '#FFFFFF';
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #colorList {
            margin-top: 10px;
            list-style: none;
        }

        #colorList li {
            display: inline-block;
            width: 24px;
            height: 24px;
            margin-right: 5px;
            border-radius: 50%;
            cursor: pointer;
        }

        #colorList li:hover {
            transform: scale(1.2);
        }


        .select-colors {
            display: flex;
            gap: 10px;
        }


        #colorInput {
            width: 430px;
        }

        #colorPicker {
            width: 50px;
            padding: 0;
        }

        #addButton {
            height: 100%;
            padding: 5px;
        }





        #sizeInput {
            text-transform: capitalize;
        }


        #sizeList {
            margin-top: 10px;
            list-style: none;
            text-transform: uppercase;
        }

        #sizeList li {
            display: inline-block;
            background-color: lightgray;
            padding: 5px;
            margin-right: 5px;
            border-radius: 5px;
            cursor: pointer;
        }

        #sizeList li:hover {
            background-color: gray;
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
            <input type="text" name="colors" id="colorsInput" value="">
        </div>

        <div class="input-fields">
            <label for="size">Sizes:</label><br>
            <input type="text" id="sizeInput" placeholder="Enter size and press Enter">
            <ul id="sizeList"></ul>
            <input type="input" name="sizes" id="sizesInput" value="">
        </div>

        <input type="file" name="photo">

        <div><button type="submit" name="submit">Submit</button></div>
    </form>

    <div>
        <?php
        include ('dbconnect.php');
        session_start();

        // Retrieve data from the database
        $sql = "SELECT * FROM example";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $name = $row['name'];
                $colors = unserialize($row['colors']);
                $sizes = $row['sizes']; // Retrieve sizes directly
        
                // Display the retrieved data
                echo "Name: $name<br>";
                echo "Colors: ";
                foreach ($colors as $color) {
                    echo "<span style='background-color:$color; padding: 5px; margin-right: 5px; border-radius: 50%;'></span>";
                }
                echo "<br>";
                echo "Sizes: ";
                $sizeArray = explode(' ', $sizes); // Convert sizes string to an array
                echo implode(' ', $sizeArray); // Display sizes individually with spaces
                echo "<br><br>";
            }
        } else {
            echo "No data found in the database.";
        }
        ?>
    </div>







    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const colorInput = document.getElementById('colorInput');
            const colorPicker = document.getElementById('colorPicker');
            const addButton = document.getElementById('addButton');
            const colorList = document.getElementById('colorList');
            const sizeInput = document.getElementById('sizeInput');
            const sizeList = document.getElementById('sizeList');
            const colorsInput = document.getElementById('colorsInput');
            const sizesInput = document.getElementById('sizesInput');
            const colors = [];
            const sizes = [];

            // Add event listener to the "Add" button
            addButton.addEventListener('click', () => {
                handleAddColor();
            });



            colorInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Prevent the default behavior on Enter key press
                }
            });

            // Add event listener to colorPicker
            colorPicker.addEventListener('change', handleColorPicker);

            // Function to handle adding a color
            function handleAddColor() {
                const colorValue = colorInput.value.trim();
                const colorPickerValue = colorPicker.value;

                if (colorValue !== '') {
                    const hexColor = convertColorToHex(colorValue); // Convert color name to hex color code
                    if (hexColor) {
                        const listItem = document.createElement('li');
                        listItem.style.backgroundColor = hexColor;
                        listItem.textContent = ''; // Remove the text content
                        listItem.addEventListener('click', function () {
                            removeColor(hexColor);
                        });
                        colorList.appendChild(listItem);
                        colors.push(hexColor);
                        colorsInput.value = colors.join(' ');
                        colorInput.value = '';
                    } else {
                        alert('Invalid color! Please enter a valid color.');
                    }
                }
            }

            // Function to convert color name to hex color code
            function convertColorToHex(color) {
                const namedColors = {
                    "green": "#008000",
                    "blue": "#0000FF",
                    "red": "#FF0000",
                    "yellow": "#FFFF00",
                    "orange": "#FFA500",
                    "purple": "#800080",
                    "pink": "#FFC0CB",
                    "black": "#000000",
                    "white": "#FFFFFF",
                    "gray": "#808080",
                    "brown": "#A52A2A",
                    "gold": "#FFD700",
                    "silver": "#C0C0C0",
                    "teal": "#008080",
                    "navy": "#000080",
                    "maroon": "#800000",
                    "olive": "#808000",
                    "lime": "#00FF00",
                    "aqua": "#00FFFF",
                    "fuchsia": "#FF00FF",
                    "indigo": "#4B0082",
                    "coral": "#FF7F50",
                    "crimson": "#DC143C",
                    "salmon": "#FA8072",
                    "orchid": "#DA70D6",
                    "lavender": "#E6E6FA",
                    "violet": "#EE82EE",
                    "peru": "#CD853F",
                    "sienna": "#A0522D",
                    "tomato": "#FF6347",
                    "turquoise": "#40E0D0",
                    "steelblue": "#4682B4",
                    "darkslategray": "#2F4F4F",
                    "mediumseagreen": "#3CB371",
                    "mediumvioletred": "#C71585",
                    "darkgoldenrod": "#B8860B",
                    "mediumorchid": "#BA55D3",
                    "mediumturquoise": "#48D1CC",
                    "darkkhaki": "#BDB76B",
                    "mediumblue": "#0000CD",
                    "darkcyan": "#008B8B",
                    "darkorchid": "#9932CC",
                    "darkred": "#8B0000",
                    "darkgreen": "#006400",
                    "darkmagenta": "#8B008B",
                    "darkolivegreen": "#556B2F",
                    "darkorange": "#FF8C00",
                    "darkslateblue": "#483D8B",
                    "darkviolet": "#9400D3",
                    "aliceblue": "#F0F8FF",
                    "antiquewhite": "#FAEBD7",
                    "aquamarine": "#7FFFD4",
                    "azure": "#F0FFFF",
                    "beige": "#F5F5DC",
                    "bisque": "#FFE4C4",
                    "blanchedalmond": "#FFEBCD",
                    "cadetblue": "#5F9EA0",
                    "chartreuse": "#7FFF00",
                    "chocolate": "#D2691E",
                    "cornflowerblue": "#6495ED",
                    "cornsilk": "#FFF8DC",
                    "darkblue": "#00008B",
                    "darkgray": "#A9A9A9",
                    "darkgrey": "#A9A9A9",
                    "darkseagreen": "#8FBC8F",
                    "dodgerblue": "#1E90FF",
                    "firebrick": "#B22222",
                    "floralwhite": "#FFFAF0",
                    "forestgreen": "#228B22",
                    "gainsboro": "#DCDCDC",
                    "ghostwhite": "#F8F8FF",
                    "honeydew": "#F0FFF0",
                    "hotpink": "#FF69B4",
                    "indianred": "#CD5C5C",
                    "ivory": "#FFFFF0",
                    "khaki": "#F0E68C",
                    "lawngreen": "#7CFC00",
                    "lemonchiffon": "#FFFACD",
                    "lightblue": "#ADD8E6",
                    "lightcoral": "#F08080",
                    "lightcyan": "#E0FFFF",
                    "lightgray": "#D3D3D3",
                    "lightgreen": "#90EE90",
                    "lightpink": "#FFB6C1",
                    "lightsalmon": "#FFA07A",
                    "lightseagreen": "#20B2AA",
                    "lightskyblue": "#87CEFA",
                    "lightslategray": "#778899",
                    "lightslategrey": "#778899",
                    "lightsteelblue": "#B0C4DE",
                    "lightyellow": "#FFFFE0",
                    "linen": "#FAF0E6",
                    "mediumaquamarine": "#66CDAA",
                    "mediumspringgreen": "#00FA9A",
                    // Add 100 more color conversions...
                };

                const lowercaseColor = color.toLowerCase();
                if (namedColors.hasOwnProperty(lowercaseColor)) {
                    return namedColors[lowercaseColor];
                }

                if (/^#[a-f0-9]{6}$/i.test(color)) {
                    return color;
                }

                return null; // Return null for invalid colors
            }


            // Function to remove a color from the list
            function removeColor(color) {
                const index = colors.indexOf(color);
                if (index > -1) {
                    colors.splice(index, 1);
                    colorsInput.value = colors.join(' ');
                }
                event.target.remove();
            }

            // Function to check if a color is valid
            function isValidColor(color) {
                const namedColors = [
                    "green", "blue", "red", "yellow", "orange", "purple", "pink", "black", "white", "gray", "brown",
                    "gold", "silver", "teal", "navy", "maroon", "olive", "lime", "aqua", "fuchsia", "indigo",
                    "coral", "crimson", "salmon", "orchid", "lavender", "violet", "peru", "sienna", "tomato", "turquoise",
                    "steelblue", "darkslategray", "mediumseagreen", "mediumvioletred", "darkgoldenrod", "mediumorchid", "mediumturquoise", "darkkhaki",
                    "mediumblue", "darkcyan", "darkorchid", "darkred", "darkgreen", "darkmagenta", "darkolivegreen", "darkorange", "darkslateblue", "darkviolet",
                    "aliceblue", "antiquewhite", "aquamarine", "azure", "beige", "bisque", "blanchedalmond", "cadetblue", "chartreuse", "chocolate",
                    "cornflowerblue", "cornsilk", "darkblue", "darkgray", "darkgrey", "darkseagreen", "dodgerblue", "firebrick", "floralwhite", "forestgreen",
                    "gainsboro", "ghostwhite", "honeydew", "hotpink", "indianred", "ivory", "khaki", "lawngreen", "lemonchiffon", "lightblue",
                    "lightcoral", "lightcyan", "lightgray", "lightgreen", "lightpink", "lightsalmon", "lightseagreen", "lightskyblue", "lightslategray", "lightslategrey",
                    "lightsteelblue", "lightyellow", "linen", "mediumaquamarine", "mediumspringgreen", "mintcream", "mistyrose", "moccasin", "oldlace", "orangered",
                    "palegoldenrod", "palegreen", "paleturquoise", "palevioletred", "papayawhip", "peachpuff", "plum", "powderblue", "rebeccapurple", "rosybrown",
                    "saddlebrown", "sandybrown", "seagreen", "seashell", "silver", "skyblue", "slateblue", "slategray", "slategrey", "snow",
                    "springgreen", "tan", "thistle", "wheat", "whitesmoke", "yellowgreen"
                ]; // Add more basic color names as needed

                if (namedColors.includes(color.toLowerCase())) {
                    return true;
                }

                return /^#[0-9A-F]{6}$/i.test(color);
            }


            // Function to handle setting colorInput value to colorPicker value
            function handleColorPicker() {
                colorInput.value = colorPicker.value;
            }

            // Function to handle removing a color item
            function handleColorItemClick(event) {
                event.target.remove();
            }

            // Function to add a color to the list
            function addColorToList(color) {
                const li = document.createElement('li');
                li.style.backgroundColor = color;
                li.addEventListener('click', handleColorItemClick);
                colorList.appendChild(li);
            }















            // Add event listener to the size input field
            sizeInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Prevent the default behavior on Enter key press
                    handleAddSize();
                }
            });

            // Function to handle adding a size
            function handleAddSize() {
                const sizeValue = sizeInput.value.trim();

                if (sizeValue !== '') {
                    addSizeToList(sizeValue);
                    sizeInput.value = '';
                    sizes.push(sizeValue);
                    sizesInput.value = sizes.join(' ');
                }
            }

            // Function to remove a size from the list
            function removeSize(event) {
                const size = event.target.textContent;
                const index = sizes.indexOf(size);
                if (index > -1) {
                    sizes.splice(index, 1);
                    sizesInput.value = sizes.join(' ');
                }
                event.target.remove();
            }



            // Function to add a size to the list
            function addSizeToList(size) {
                const li = document.createElement('li');
                li.textContent = size;
                li.addEventListener('click', removeSize);
                sizeList.appendChild(li);
            }

            // Add event listener to the name input field
            const nameInput = document.getElementById('name');
            nameInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Prevent the form submission
                }
            });

        });
    </script>
</body>

</html>