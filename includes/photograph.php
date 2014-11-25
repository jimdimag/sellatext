<?php
require_once(LIB_PATH.DS.'database.php');

class Photograph extends DatabaseObject {
    
    protected static $table_name = "photographs";
    protected static $db_fields = array('id', 'filename', 'type', 'size', 'caption');
    public $id;
    public $filename;
    public $type;
    public $size;
    public $caption;
    
    private $temp_path;
    protected $upload_dir = "images";
    public $errors = array();
    
    protected $upload_errors = array(
    UPLOAD_ERR_OK       => "No errors.",
    UPLOAD_ERR_INI_SIZE => "Larger than upload_max_filesize.",
  UPLOAD_ERR_FORM_SIZE  => "Larger than form MAX_FILE_SIZE.",
  UPLOAD_ERR_PARTIAL    => "Partial upload.",
  UPLOAD_ERR_NO_FILE    => "No file.",
  UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
  UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
  UPLOAD_ERR_EXTENSION  => "File upload stopped by extension."
);

public function attach_file($file) {
    //Error checking
   if(!$file || empty($file) || !is_array($file)) {
       $this->errors[] = "No file was uploaded.";
       return FALSE;
   } elseif($file['error'] !=0) {
       $this->errors[] = $this->upload_errors[$file['error']];
       return false;
   } else {
   $this->temp_path = $file['tmp_name'];
   $this->filename  = basename($file['name']);
   $this->type      = $file['type'];
   $this->size      = $file['size'];
   return TRUE;
   }
}

public function save() {
    if(isset($this->id)) {
        $this->update();
    } else {
        //can't save if there are errors
        if(!empty($this->errors)) {return TRUE;}
        
        //Make sure the caption is not too long
        if(strlen($this->caption) >255) {
            $this->errors[] = "The caption can only be 255 characters long.";
            return false;
        }
        
        //Cant save without filename and temp loccation
        if(empty($this->filename) || empty($this->temp_path)) {
            $this->errors[] = "The file location was not available.";
            return false;
        }
        
        //Determine target path
        $target_path = SITE_ROOT.DS.'public'.DS.$this->upload_dir.DS.$this->filename;
        
        //Make sure file does not already exist
        if(file_exists($target_path)) {
          $this->errors[] = "The file {$this->filename} already exists.";
            return false;  
        }
        
        //Attempt to move the file
        if(move_uploaded_file($this->temp_path, $target_path)) {
          //if success
            if($this->create()) {
                unset($this->temp_path);
                return true;
            }
        } else {
            //failure
            $this->errors[] = "The file upload failed, possibly to incorrect permissions on the upload folder.";
            return false; 
        }
        
    }
}

public function destroy() {
    //first remove the database entry
    if($this->delete()) {
        //then remove the file
        $target_path = SITE_ROOT.DS.'public'.DS.$this->image_path();
        return unlink($target_path) ? TRUE : FALSE;
    } else {
        //database delete failed
        return FALSE;
    }
}

public function image_path() {
    return $this->upload_dir.DS.$this->filename;
}

public function size_as_text() {
    if($this->size < 1024) {
        return "{$this->size} bytes";
    } elseif($this->size < 1048576) {
        $size_kb = round($this->size/1024);
        return "{$size_kb} KB";
    } else {
        $size_mb = round($this->size/1048576, 1);
        return "{$size_mb} MB";
    }
}


public function comments() {
    return Comment::find_comments_on($this->id);
}

} // Class

?>