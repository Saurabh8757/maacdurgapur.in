import sys
try:
    from PIL import Image
except ImportError:
    print("Pillow not installed")
    sys.exit(1)

def remove_white_background(img_path, out_path):
    try:
        img = Image.open(img_path)
        img = img.convert("RGBA")
        datas = img.getdata()
        
        newData = []
        # tolerance for white
        for item in datas:
            # If r,g,b > 240, it's very bright white/grey background
            if item[0] > 240 and item[1] > 240 and item[2] > 240:
                # Keep anti-aliasing: if it's perfectly white (255,255,255) make it alpha=0
                # But for a simpler clean cut, just make anything > 240 completely transparent
                newData.append((255, 255, 255, 0))
            else:
                newData.append(item)
                
        img.putdata(newData)
        
        # Crop to the actual robot
        bbox = img.getbbox()
        if bbox:
            img = img.crop(bbox)
            
        img.save(out_path, "PNG")
        print("Success")
    except Exception as e:
        print(f"Error: {str(e)}")
        sys.exit(1)

img_path = r"c:\xampp\htdocs\maacdurgapur\public\frontend\images\maac\icons\index-logo.png"
out_path = r"c:\xampp\htdocs\maacdurgapur\public\frontend\images\maac\icons\transparent-logo.png"
remove_white_background(img_path, out_path)
