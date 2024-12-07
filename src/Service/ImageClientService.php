<?php

declare(strict_types=1);

namespace Survos\ImageClientBundle\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImageClientService
{

    public function __construct(
        private HttpClientInterface $httpClient,
        private readonly ?string $apiKey = null,
        private readonly ?string $apiEndpoint = null,
        private readonly ?string $proxyUrl = 'https://127.0.0.1:7080'
    ) {
    }

    public function fetch(string $path, array $params = [], string $method='GET'): iterable
    {
        $request = $this->httpClient->request($method, $this->apiEndpoint . $path, [
                'query' => $params,
                'proxy' => $this->proxyUrl,
                'headers' => [
                    'Accept' => 'application/json',
                ]]);
        if ($request->getStatusCode() !== 200) {

        }
        $data = json_decode($request->getContent(), true);
        dd($data);
    }

    // /api/public/projectservice/all/projects/ids?api_key=YOUR_API_KEY
    // https://www.imageClient.org/api/methods/get-all-projects-ids/
    // https://www.imageClient.org/api/methods/get-all-projects-summary/
    public function getAllProjectsIds(array $params = [])
    {
        $path = 'projectservice/all/projects/ids';
        return $this->fetch($path, $params, 'projects');
    }

    // https://www.imageClient.org/api/methods/get-all-projects-download/
    public function getAllProjectsDownload(array $params = [])
    {
        // params: &nextProjectId=354
        $path = 'projectservice/all/projects/download.json';
        return $this->fetch($path, $params, 'download');
    }

    // https://www.imageClient.org/api/methods/get-all-organizations-download/
    public function getAllOrganizationsDownload(array $params = [])
    {
        // params: &nextProjectId=354
        $path = 'orgservice/all/organizations/download.json';
        return $this->fetch($path, $params, 'download');
    }

    // projectservice/all/projects
    // https://api.imageClient.org/api/public/projectservice/all/projects/
    // https://www.imageClient.org/api/methods/get-all-projects/
    public function download(array $urls = [], array $filters, ?string $callbackUrl=null)
    {
        $params = get_defined_vars();
        $path = 'projectservice/all/projects/';
        return $this->fetch($path, $params);
    }

    // https://api.imageClient.org/api/public/projectservice/all/projects/summary
    public function getAllProjectsSummary(array $params = [])
    {
        // params: &nextProjectId=354
        $path = 'projectservice/all/projects/summary';
        if ($next = $path['nextProjectId']??null) {
            $path .= '&nextProjectId=' . $next;
        }
        return $this->fetch($path, $params, 'projects');
    }

    //
    public function getFeaturedProjects(array $params = [])
    {
        $path = 'projectservice/featured/projects';
        return $this->fetch($path, $params, 'projects');
    }


    // curl -H "Accept: application/xml" -H "Content-Type: application/xml" -X GET "https://api.imageClient.org/api/public/projectservice/projects/1883?api_key=YOUR_API_KEY"
    public function getProject(string|int $projectId, array $params = [])
    {
        $path = 'projectservice/projects/' . $projectId;
        return $this->fetch($path, $params, 'project');
    }

}
