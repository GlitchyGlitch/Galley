<?

require __DIR__ . "/../exceptions.php";

class Model
{
  public $id;
  public $created_at;

  public function json_dump()
  {
    return json_encode($this);
  }

  public function json_load($json)
  {
    $data = json_decode($json);
    if (json_last_error() !== JSON_ERROR_NONE) {
      throw new LoadingException();
    }
    $this->array_load($data);
    return false;
  }

  public function array_dump()
  {
    $dump = (array) $this;
    $dump = array_filter($dump, fn ($value) => !is_null($value) && $value !== '');
    return $dump;
  }

  public function array_load($data)
  {
    foreach ($data as $key => $value) $this->{$key} = $value;
    if (isset($this->id) && !preg_match('//u', $this->id)) {
      $this->id = bin_to_uuid($this->id);
    }
    if (isset($this->owner) && !preg_match('//u', $this->owner)) {
      $this->owner = bin_to_uuid($this->owner);
    }
  }
}

class ModelList
{
  private $array = [];
  private $type;
  public function add($object)
  {
    array_push($this->array, $object);
  }

  protected function set_type($type)
  {
    $this->type = $type;
  }

  public function array_dump()
  {
    $result = [];
    foreach ($this->array as $model) {
      array_push($result, $model->array_dump());
    }
    return $result;
  }
  public function array_load($data)
  {
    $array = [];
    foreach ($data as $object_data) { //FIXME: never executing this foreach
      $object = new $this->type();
      $object->array_load($object_data);
      array_push($array, $object);
    }

    $this->array = $array;
    return $this->array;
  }

  public function json_dump()
  {
    return json_encode($this->array_dump());
  }
}
