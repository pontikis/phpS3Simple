<?php
require_once C_CLASS_AWS_SDK_PHP_PATH;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;

/**
 * Class phpS3Simple
 *
 * Simple aws-sdk-php wrapper for Amazon S3
 * https://github.com/pontikis/phpS3Simple
 *
 * @author     Christos Pontikis http://pontikis.net
 * @copyright  Christos Pontikis
 * @license    MIT http://opensource.org/licenses/MIT
 * @version    0.3.0 (29 Jul 2017)
 *
 */
class phpS3Simple {

	private $ds;
	private $s3_client;

	private $last_error;

	/**
	 * phpS3Simple constructor.
	 * @param array $options
	 */
	public function __construct(dacapo $ds, array $options) {

		$this->ds = $ds;
		$this->last_error = null;

		try {

			$opt = array(
				'version' => $options['version'],
				'region' => $options['region'],
				'credentials' => array(
					'key' => $options['key'],
					'secret' => $options['secret']
				)
			);
			// create S3 client
			$this->s3_client = new S3Client($opt);

		} catch(S3Exception $e) {
			$this->last_error = $e->getMessage();
		} catch(AwsException $e) {
			$this->last_error = $e->getMessage();
		}

	}

	public function getS3Client() {
		return $this->s3_client;
	}

	public function getLastError() {
		return $this->last_error;
	}

	/**
	 * @param array $options
	 * @return bool|string
	 */
	public function createPresignedURL(array $options) {

		$opt = array(
			'Bucket' => $options['Bucket'],
			'Key' => $options['Key']
		);

		$use_memcached = array_key_exists('cache_url_expiration', $options) ? true : false;

		if($use_memcached) {
			$url = $this->ds->pull_from_memcached($opt['Key']);
			if($url) {
				return $url;
			}
		}

		try {

			$cmd = $this->s3_client->getCommand('GetObject', $opt);

			$request = $this->s3_client->createPresignedRequest($cmd, $options['expire']);

			$url = (string)$request->getUri();

		} catch(S3Exception $e) {
			$this->last_error = $e->getMessage();
			return false;
		} catch(AwsException $e) {
			$this->last_error = $e->getMessage();
			return false;
		}

		if($use_memcached) {
			$this->ds->push_to_memcached($opt['Key'], $url, $options['cache_url_expiration']);
		}

		return $url;

	}


	/**
	 * @param array $options
	 * @return bool
	 */
	public function putObject(array $options) {

		// required parmas
		$opt = array(
			'Bucket' => $options['Bucket'],
			'Key' => $options['Key']
		);

		// optional parmas
		if(array_key_exists('Body', $options)) {
			$opt['Body'] = $options['Body'];
		}
		if(array_key_exists('Acl', $options)) {
			$opt['Acl'] = $options['Acl'];
		}
		if(array_key_exists('ServerSideEncryption', $options)) {
			$opt['ServerSideEncryption'] = $options['ServerSideEncryption'];
		}

		try {

			$this->s3_client->putObject($opt);

			return true;

		} catch(S3Exception $e) {
			$this->last_error = $e->getMessage();
			return false;
		} catch(AwsException $e) {
			$this->last_error = $e->getMessage();
			return false;
		}

	}

	/**
	 * @param array $options
	 * @return bool
	 */
	public function getObject(array $options) {

		// required parmas
		$opt = array(
			'Bucket' => $options['Bucket'],
			'Key' => $options['Key']
		);

		// optional parmas
		if(array_key_exists('SaveAs', $options)) {
			$opt['SaveAs'] = $options['SaveAs'];
		}

		try {

			$this->s3_client->getObject($opt);

			return true;

		} catch(S3Exception $e) {
			$this->last_error = $e->getMessage();
			return false;
		} catch(AwsException $e) {
			$this->last_error = $e->getMessage();
			return false;
		}

	}


	/**
	 * @param array $options
	 * @return bool
	 */
	public function deleteObject(array $options) {

		// required parmas
		$opt = array(
			'Bucket' => $options['Bucket'],
			'Key' => $options['Key']
		);

		try {

			$this->s3_client->deleteObject($opt);

			return true;

		} catch(S3Exception $e) {
			$this->last_error = $e->getMessage();
			return false;
		} catch(AwsException $e) {
			$this->last_error = $e->getMessage();
			return false;
		}

	}

}