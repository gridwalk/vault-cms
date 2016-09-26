outputImage = function(image){

  // chance to enclose in a div
  _imgParent = _container

  if( image.filesize.toString()[0] < 5 ){
    
    _imgParent = document.createElement('div')
    _imgParent.style.backgroundImage = 'url('+image.abs_path.replace(/ /g,'%20')+')'
    _imgParent.style.overflow = 'hidden'
    _imgParent.style.width = '100%'

    // chance for tiled pattern
    _imgParent.style.backgroundSize = '105px'
    if( isNumeric( image.hash[2] ) ){

      unit = image.hash[2] > 5 ? 'px' : '%'

      _imgParent.style.backgroundSize = image.hash[2] * 10 + unit
    }

    _container.appendChild(_imgParent)
  }

  // width
  percent_width = 100
  if( isNumeric( image.hash[0] ) ){
    percent_width = ((image.hash[0]*1) + 1) * 10; 
  }

  // float
  float = 'none'
  if( isNumeric( image.hash[2] ) ){
    float = 'right'
  }

  // negative margin bottom
  marginBottom = 0
  if( isNumeric( image.hash[1] && i !== 0 ) ){
    marginBottom = ( image.hash[1] * -1) * 20
  }

  _img = document.createElement('img')

  _img.src                = image.abs_path
  _img.style.width        = percent_width+'%'
  _img.style.float        = float
  _img.style.marginTop    = marginBottom

  _imgParent.appendChild(_img)
}