<?

require __DIR__ . '/../exceptions.php';

class Model
{
  // This fields can be declared in extenstion of this class, and covered automatically.
  // public $id;
  // public $created_at;

  public function json_dump(): string
  {
    return json_encode($this->array_dump());
  }

  public function json_load($json): void
  {
    $data = json_decode($json);
    if (json_last_error() !== JSON_ERROR_NONE) {
      throw new LoadingException();
    }
    $this->array_load($data);
  }

  public function array_dump(): array
  {
    $dump = (array) $this;
    $dump = array_filter($dump, fn ($value) => !is_null($value) && $value !== '');
    return $dump;
  }

  public function array_load($data): void
  {
    array_walk($data, function (&$value, $key) {
      $value = (str_ends_with($key, 'id')) ? bin_to_uuid($value) : $value; //TODO: Handle case when in field is no UUID
    });
    foreach ($data as $key => $value) $this->{$key} = $value;
  }
}

class ModelList
{
  private $array = [];
  private $type;
  public function add($object): void
  {
    array_push($this->array, $object);
  }

  protected function set_type($type): void
  {
    $this->type = $type;
  }

  public function array_dump(): array
  {
    $result = [];
    foreach ($this->array as $model) {
      array_push($result, $model->array_dump());
    }
    return $result;
  }
  public function array_load($data): void
  {
    $array = [];
    foreach ($data as $object_data) { //FIXME: never executing this foreach
      $object = new $this->type();
      $object->array_load($object_data);
      array_push($array, $object);
    }

    $this->array = $array;
  }

  public function json_dump()
  {
    return json_encode($this->array_dump());
  }
}

interface ModelInterface
{
  public function validate(bool $input_only): bool;
  public function json_dump(): string;
  public function json_load(string $json);
  public function array_dump(): array;
  public function array_load(array $data);
}
