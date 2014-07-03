<?php
if($_SERVER['HTTP_HOST'] == 'cag.deletedmuseums.org') {
  header( 'Location: http://cag.deletedmuseums.org/page.php?u=http%3A%2F%2Fchristchurchartgallery.org.nz%2F');
}
?>
  
  <form action='/site.php' method='get' >
    Hello, enter a url to delete
    <input type='text' name='u' />
    <input type='submit' />
  </form>

  <br /><br />
  Or have a look at <a href='page.php?u=http%3A%2F%2Fchristchurchartgallery.org.nz%2F'>Christchurch Art Gallery</a>
