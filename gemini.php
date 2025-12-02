<?php
header("Content-Type: application/json");

$mensagem = $_POST["mensagem"] ?? "";

$api_key = "COLOQUE_SUA_API_KEY_AQUI";

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $api_key;

$prompt_ia = "
Você é um assistente virtual que explica conceitos básicos de enfermagem com IoT.
Fale sempre de forma simples, como se estivesse falando para iniciantes.
Dê exemplos práticos sobre como a internet das coisas está sendo usada no campo da enfermagem.
";

$data = [
  "contents" => [
    [
      "parts" => [
        ["text" => $prompt_ia . "\nMensagem: " . $mensagem]
      ]
    ]
  ]
];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_error($ch)) {
  echo json_encode(["resposta" => "❌ Erro ao conectar à IA: " . curl_error($ch)]);
  exit;
}

curl_close($ch);

$json = json_decode($response, true);

$resposta = $json["candidates"][0]["content"]["parts"][0]["text"]
?? "❌ A IA não respondeu. Verifique sua API KEY.";

echo json_encode(["resposta" => $resposta]);