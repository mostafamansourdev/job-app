<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;
use Spatie\PdfToText\Pdf;

class ResumeAnalysisService
{
  public function extractResumeInformation(string $fileUrl)
  {

    try {
      // extract row text from resume pdf file (read file from given url and extract text)
      $rowText = $this->extractTextFromPdf($fileUrl);

      Log::debug("Successfully Extracted Text: " . strlen($rowText) . " characters.");


      // use openAi Api to organize and structure the extracted text into json format
      // Output: summery, skills, experience, education -----> JSON format
      // use less tokens as possible
      $response = OpenAI::chat()->create([
        'model' => 'gpt-4o-mini',
        'messages' => [
          [
            'role' => 'system',
            'content' => 'You are a precise resume parser. You will receive raw text extracted from a resume in PDF format. Your task is to analyze this text and extract key information including a summary, skills, experience, and education. Please extract exactly the same information without altering or adding any interpolations to the text and structure the extracted information into a JSON object and with the following fields: summary, skills, experience, and education. Ensure that the JSON is well-formatted and easy to read.'
          ],
          [
            'role' => 'user',
            'content' => "Here is the raw text extracted from the resume:\n\n" . $rowText . "\n\nPlease provide the structured JSON object with the keys 'summary', 'skills', 'experience', and 'education'. and return empty fields as empty strings if no data found."
          ]
        ],
        "response_format" => [
          "type" => "json_object"
        ],
        'temperature' => 0.1, // sets creativity level of the response (0 = precise, 1 = creative)
        'max_tokens' => 1000,
      ]);

      $resultContent = $response->choices[0]->message->content;



      $parseJson = json_decode($resultContent, true);

      if (json_last_error() !== JSON_ERROR_NONE) {
        Log::error("Failed to parse JSON from OpenAI response: " . json_last_error_msg());
        throw new \Exception("Failed to parse JSON from OpenAI response");
      }

      Log::debug("OpenAI Response: " . $resultContent);

      // validate and return structured json object

      $requiredKeys = ['summary', 'skills', 'experience', 'education'];
      $missingKeys = array_diff($requiredKeys, array_keys($parseJson));

      if (count($missingKeys) > 0) {
        Log::error("Missing keys in OpenAI response: " . implode(", ", $missingKeys));
        throw new \Exception("Missing keys in OpenAI response: " . implode(", ", $missingKeys));
      }

      // return the structured json object
      return $parseJson;
    } catch (\Exception $e) {
      Log::error("Error in extractResumeInformation: " . $e->getMessage());
      // return face data
      return [
        'summary' => '',
        'skills' => '',
        'experience' => '',
        'education' => ''
      ];
    }
  }

  public function analyzeResume($jobVacancy, $resumeData)
  {
    try {
      $jobDetails = json_encode([
        'job_title' => $jobVacancy->title,
        'job_description' => $jobVacancy->description,
        'job_location' => $jobVacancy->location,
        'job_type' => $jobVacancy->type,
        'job_salary' => $jobVacancy->salary,
      ]);

      $resumeDetails = json_encode($resumeData);

      // generate score based on resume compared to job vacancy
      $response = OpenAI::chat()->create([
        'model' => 'gpt-4o-mini',
        'messages' => [
          [
            'role' => 'system',
            'content' => "You are an expert job application evaluator and HR manager. 
            You will receive details about a job vacancy and a candidate's resume.
            Your task is to analyze the resume in the context of the job vacancy and generate a score from 0 to 100 indicating how well the candidate fits the job requirements.
            and generate brief feedback explaining the score.
            Consider factors such as relevant skills, experience, and education.
            Provide a brief explanation for the score you assign.
            the score should reflect the candidate's suitability for the job based on the provided information.
            the response should be in JSON format with the keys 'aiGeneratedScore' and 'aiGeneratedFeedback'."
          ],
          [
            'role' => 'user',
            'content' => "Job Details: {$jobDetails}
            Resume Details: {$resumeDetails}
            Please provide a JSON object with the following structure:
            {
              'aiGeneratedScore': <numeric score between 0 and 100>,
              'aiGeneratedFeedback': <brief explanation of the score>
            }"
          ]
        ],
        "response_format" => [
          "type" => "json_object"
        ],
        'temperature' => 0.1, // sets creativity level of the response (0 = precise, 1 = creative)
        'max_tokens' => 1000,
      ]);

      $resultContent = $response->choices[0]->message->content;
      Log::debug("OpenAI Analysis Response: " . $resultContent);
      $parseJson = json_decode($resultContent, true);

      if (json_last_error() !== JSON_ERROR_NONE) {
        Log::error("Failed to parse JSON from OpenAI analysis response: " . json_last_error_msg());
        throw new \Exception("Failed to parse JSON from OpenAI analysis response");
      }

      if (!isset($parseJson['aiGeneratedScore']) || !isset($parseJson['aiGeneratedFeedback'])) {
        Log::error("Missing keys in OpenAI analysis response");
        throw new \Exception("Missing keys in OpenAI analysis response");
      }

      return [
        'aiGeneratedScore' => $parseJson['aiGeneratedScore'],
        'aiGeneratedFeedback' => $parseJson['aiGeneratedFeedback'],
      ];
    } catch (\Exception $e) {
      Log::error('' . $e->getMessage());
      return [
        'aiGeneratedScore' => 0,
        'aiGeneratedFeedback' => 'Unable to evaluate resume at this time.',
      ];
    }
  }

  private function extractTextFromPdf(string $fileUrl): string
  {
    // spatie PDF to text extraction logic here
    // Retrieve the PDF file from cloud storage to a temporary local file
    $tempFile = tempnam(sys_get_temp_dir(), 'pdf');
    $filePath = parse_url($fileUrl, PHP_URL_PATH);

    if (!$filePath) {
      throw new \Exception("Invalid file URL");
    }

    // Get the file name from the URL
    $fileName = basename($filePath);
    // Construct the storage path
    $storagePath =  "resumes/{$fileName}";

    // throw exception if file does not exist in cloud storage
    if (!Storage::disk("cloud")->exists($storagePath)) {
      throw new \Exception("File does not exist in cloud storage");
    }

    // Copy the file from cloud storage to local temporary file
    $pdfContent = Storage::disk("cloud")->get($storagePath);

    // throw exception if failed to read pdf content
    if (!$pdfContent) {
      throw new \Exception("Failed to read PDF content");
    }
    // Write the PDF content to the temporary file
    file_put_contents($tempFile, $pdfContent);

    // check if pdf-to-text package is installed in system (linux (ubuntu,debian, santos, fedora, redhat), mac)
    $pdfToTextPath = ['/usr/local/bin/pdftotext', '/usr/bin/pdftotext', '/bin/pdftotext', '/opt/homebrew/bin/pdftotext'];
    $pdftotext = null;
    foreach ($pdfToTextPath as $path) {
      if (file_exists($path)) {
        $pdftotext = $path;
        break;
      }
    }
    if (!$pdftotext) {
      throw new \Exception("pdftotext utility is not installed on the server/system. Please install it to proceed.");
    }


    // extract text using spatie/pdf-to-text package
    $instance = new Pdf($pdftotext);

    $text = $instance->setPdf($tempFile)->text();

    // cleanup temporary file
    unlink($tempFile);

    return $text;
  }
}
