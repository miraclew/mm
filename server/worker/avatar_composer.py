'''
Created on Jun 3, 2013

@author: aaaa
'''
import Image

img_width = 120
img_heigth = 120

def compose(imgs, out='out.png'):
    background = Image.new('RGBA', (img_width,img_heigth), (255, 255, 255, 255))
    # calc grid layout    
    count = len(imgs)
    for index in range(0, count):
        size = calc_imge_size(count)
        offset = calc_imge_origin(index, count)
        img=Image.open(imgs[index],'r')
        img = img.resize(size, Image.ANTIALIAS)
        background.paste(img,offset)
        background.save(out)

    
def calc_imge_origin(index, imgs_count):
    layout = calc_layout(imgs_count)
    img_size = calc_imge_size(imgs_count)
    x = (index % layout[1]) * img_size[0]
    y = (index / layout[1]) * img_size[0]
    return (x, y)
    
def calc_imge_size(imgs_count):
    rows = max(calc_layout(imgs_count))
    return (img_width/rows, img_heigth/rows)    
    
def calc_layout(imgs_count):    
    dt = { 3:(2,2), 4:(2,2), 5:(2,3), 6:(2,3)}
    if imgs_count < 3:
        return (1,1)
    elif imgs_count <= 6:
        return dt[imgs_count]
    else:
        return (3,3)

def main():
    pwd = '/Users/aaaa/git/mm/mm/avatar'
    imgs = list()
    for x in range(1,11):
        imgs.append('%s/%d.png' % (pwd, x))
#    print imgs    
    compose(imgs[0:3], '1.png')
    compose(imgs[0:4], '2.png')
    compose(imgs[0:5], '3.png')
    compose(imgs[0:6], '4.png')
    compose(imgs[0:7], '5.png')
    compose(imgs[0:8], '6.png')
    compose(imgs[0:9], '7.png')
    

if __name__ == '__main__':
    main()