outputVideo = function(video){
  _vidBox = document.createElement('div')
  _vidBox.style.width = '100%'
  _vidBox.style.textAlign = 'center'

  _video  = document.createElement('video')
  _video.src = video.abs_path
  _video.controls = true
  _video.style.display = 'inline-block'
  _video.style.width = '100%'

  _vidBox.appendChild(_video)
  _container.appendChild(_vidBox)
}