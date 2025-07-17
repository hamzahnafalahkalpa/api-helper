<?php

namespace Hanafalah\ApiHelper;

use Illuminate\Http\Request as HttpRequest;
use Hanafalah\LaravelSupport\Concerns\Support as Support;

class ApiHelper
{
  use Support\HasArray;
  use Support\HasResponse;
  use Support\HasRepository;

  private $__request, $__class_request, $__results = [], $__aliases;

  /**
   * Applies a given callable function to the current instance, passing it as an argument.
   *
   * @param callable $callable A function to be applied to the current instance.
   * @return self The current instance after applying the callable function.
   */
  public function scope($class, callable $callable, $alias = null): self
  {
    $this->mainScope($class, $callable, $alias);
    return $this;
  }

  /**
   * Applies a given callable function to the current instance, passing it as an argument.
   *
   * @param \Illuminate\Http\Request $request The request object.
   * @param callable $callable A function to be applied to the current instance.
   * @param string $alias The alias of the scope.
   * @return self The current instance after applying the callable function.
   */
  public function selfScope($request, callable $callable, $alias = null): self
  {
    $alias ??= 'self';
    $this->mainScope(self::class, $callable, $alias, $request);
    return $this;
  }

  /**
   * Applies a given callable function to the current instance, passing it as an argument.
   *
   * @param object|string $class The class to be used as the scope.
   * @param callable $callable A function to be applied to the current instance.
   * @param string $alias The alias of the scope.
   * @param \Illuminate\Http\Request $request The request object.
   * @return self The current instance after applying the callable function.
   */
  private function mainScope($class, callable $callable, $alias = null, $request = null): self
  {
    $this->__request = (isset($request)) ? $request->all() : request()->all();
    $result = $callable($this);
    $this->newRequest($this->__request);
    $this->__results[$this->getClassName($class, $alias)] = $result;
    $this->setRuquest($this->__request, false);
    return $this;
  }

  /**
   * Applies a given callable function to the current instance, passing it as an argument if the given condition is true.
   *
   * @param bool $condition The condition to be checked.
   * @param object|string $class The class to be used as the scope.
   * @param callable $callable A function to be applied to the current instance.
   * @param string $alias The alias of the scope.
   * @return self The current instance after applying the callable function.
   */
  public function scopeWhen(bool $condition, $class, callable $callable, $alias = null): self
  {
    if ($condition) $this->scope($class, $callable, $alias);
    return $this;
  }

  /**
   * Gets the class base name of the given class.
   *
   * @param object|string $class The class to be used as the scope.
   * @param string $alias The alias of the scope.
   * @return string The class base name of the given class or the alias if it exists.
   */
  private function getClassName($class, $alias = null)
  {
    $class_name = $this->getClassBaseName($class);

    if (isset($alias)) {
      $name = $this->checkingAlias($class, $alias);
      if (!isset($this->__aliases[$alias])) $name = $this->checkingAlias($class, $class_name);
    } else {
      $name = $this->checkingAlias($class, $class_name);
    }
    return $name;
  }

  /**
   * Checks if the given alias exists in the aliases array. If not, adds the 
   * class base name of the given class to the aliases array. If the alias 
   * does exist, returns the class name of the given class.
   * 
   * @param object $class The class to be checked.
   * @param string $name The alias to be checked.
   * @return string The class name of the given class or the alias if it exists.
   */
  private function checkingAlias($class, $name)
  {
    (!isset($this->__aliases[$name]))
      ? $this->__aliases[$name] = \class_basename($class)
      : $name = \get_class($class);
    return $name;
  }

  /**
   * Creates a new request by adding the provided parameters to the current request.
   *
   * @param array $add The parameters to add to the current request.
   * @return self
   */
  public function newRequest($add = [], $classRequest = null): HttpRequest
  {
    if (request()->has('page')) {
      $add = $this->mergeArray($add, [
        'page' => request()->get('page')
      ]);
    }
    $this->__class_request = isset($classRequest) ? $classRequest : HttpRequest::class;
    $this->setRuquest($add);
    return $this->__class_request;
  }

  /**
   * Sets the current request by adding the provided parameters.
   *
   * @param array $add The parameters to add to the current request.
   * @param bool $new Whether to create a new request or merge with the existing one.
   * @return self The current instance after setting the request.
   */
  private function setRuquest($add = [], $new = true): self
  {
    if (!$new) $this->__class_request = HttpRequest::class;
    $this->requestReplace($this->makeRequest($add));
    return $this;
  }

  /**
   * Creates a new request by merging the provided arguments with the current request.
   *
   * @param array $args The parameters to add to the current ->rurequest.
   * @return array The merged request parameters.
   */
  private function makeRequest($args)
  {
    $request = new $this->__class_request($this->filterArray($args, fn($value) => $value !== null));
    if ($this->__class_request !== HttpRequest::class) {
      $request->validate($request->rules(), $request->all());
    }
    $this->__class_request = $request;
    return $request->all();
  }

  /**
   * Replaces the current request with the provided arguments.
   *
   * @param array $args The parameters to replace the current request with.
   * @return self The current instance after replacing the request.
   */
  private function requestReplace(): self
  {
    if (isset($this->__class_request)) request()->replace($this->__class_request->all());
    return $this;
  }

  /**
   * Returns the results of the current instance.
   *
   * @return array The results of the current instance.
   */
  public function results(): array
  {
    return $this->__results;
  }

  /**
   * Sends a response back to the user containing the result of the self-scope.
   *
   * @param string|null $specific The specific key in the results to return if set.
   * @return \Illuminate\Http\JsonResponse The response that will be returned to the user.
   */
  public function response($specific = null): \Illuminate\Http\JsonResponse
  {
    $results = $this->results();
    if (isset($specific)) $results = $results[$specific];
    return $this->sendResponse($results);
  }


  /**
   * Sends a response back to the user containing the result of the self-scope.
   *
   * @return \Illuminate\Http\JsonResponse The response containing the result of the self-scope.
   */
  public function selfResponse(): \Illuminate\Http\JsonResponse
  {
    return $this->response('self');
  }
}
