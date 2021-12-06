<?php
function gen_uuidv4()
{
  $data = random_bytes(16);

  $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
  $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

  return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function uuid_to_bin($uuid)
{
  if (is_null($uuid)) {
    return;
  }
  pack("h*", str_replace('-', '', $uuid));
}

function bin_to_uuid($bin)
{
  if (is_null($bin)) {
    return;
  }
  return join("-", unpack("H8time_low/H4time_mid/H4time_hi/H4clock_seq_hi/H12clock_seq_low", $bin));
}

function isValidUuid($uuid)
{
  if (is_string($uuid) && (preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $uuid) === 1)) {
    return true;
  }

  return false;
}
