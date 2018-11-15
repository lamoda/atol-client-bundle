# ATOL PHP Client bundle
Bundle for https://github.com/lamoda/atol-client

## Installation

Usage is as simple as 

1. Install library
	```bash
	composer require lamoda/atol-client-bundle
	```

2. Register bundle and required JMS Serializer Bundle 
	```php
	// Kernel
	
	public function registerBundles()
	{
		// ...
		$bundles[] = new \Lamoda\AtolClientBundle\AtolClientBundle();
		$bundles[] = new \JMS\SerializerBundle\JMSSerializerBundle();
		// ...
	}
	```

3. Configure Guzzle client in `services.yaml`, for example:
	```yaml
	services:
		guzzle:
			class: \GuzzleHttp\Client

	```
4. Configure Symfony to enable `validator` and `enable_annotations`:
	```yaml
	framework:
        validation:
            enabled: true
            enable_annotations: true
	```
6. Add config for any clients you need:
	```yaml
	atol_client:
        clients:
            v3: # Version 3 of ATOL API. Currently deprecated and will not be supported by ATOL since 01.01.2019
                version: !php/const Lamoda\AtolClientBundle\AtolClientBundle::API_CLIENT_VERSION_3
                guzzle_client: guzzle # Link to the service that is guzzle
                guzzle_client_options: [] # Options for guzzle client (optional)
                base_url: 'http://atol_v3_url' # Base url of ATOL server
                callback_url: '' # Callback url for ATOL (see docs)
    
            default:
                version: !php/const Lamoda\AtolClientBundle\AtolClientBundle::API_CLIENT_VERSION_4
                guzzle_client: guzzle # Link to the service that is guzzle
                guzzle_client_options: [] # Options for guzzle client (optional)
                base_url: 'http://atol_v4_url' # Base url of ATOL server
	```
6. Use `@atol_client.v4` to inject atol client as a dependency
