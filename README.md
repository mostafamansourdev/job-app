# Shaghalni Job-App

# Description

-   This is the Job-Seeker client.
-   The Applicant can apply to job vacancy that are available.
-   The Applicant can upload his resume or choose an already uploaded one.
-   The Resume Is processed by OpenAI AI model (ChatGPT 4o-mini) to generate a Json file contains the resume data (summery, education, experience, and skills).
-   After choosing the resume and applying to the Job Vacancy the AI model will compare the resume data and job vacancy data to provide a score and feedback to tell if the job Seeker is a good fit for the job or not.

## About the App

-   This app is mvc client only for the **_Job Seeker_** user role.
-   The **_Admin_** and **_company owner_** can access [Job BackOffice client](https://github.com/mostafamansourdev/job-backoffice.git).
-   The database models are shared with **Job-App** and **Job-Backoffice** from the **[Job-Shared library](https://github.com/mostafamansourdev/job-shared.git)**

## Tech Stack and Technologies

-   Laravel
-   Tailwind CSS
-   AWS S3 storage or any S3 compatible service
-   openAI API
-   Spatie (Used to convert the pdf into text)

## Requirements

We are using **Spatie** package to convert the pdf into text in order for it to work we need pdf-to-text to be install in your system

### Test if installed with command which pdftotext

```bash
$ which pdftotext
```

> If it is installed it will return the path to the binary.

To install the binary you can use this command on Ubuntu or Debian:

```bash
apt-get install poppler-utils
```

On a mac you can install the binary using brew

```bash
brew install poppler
```

If you're on RedHat, CentOS, Rocky Linux or Fedora use this:

```bash
yum install poppler-utils
```
