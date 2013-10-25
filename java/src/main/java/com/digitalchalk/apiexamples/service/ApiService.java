/**
 * Copyright (C) 2012 Infinity Learning Solutions.  All rights reserved.
 * Redistribution and use in source and binary forms, with or without
 * modification, is NOT permitted unless the following conditions are
 * met:
 * 1. The redistribution of any kind or in any form must be approved
 *    in writing from an official of Infinity Learning Solutions and a third
 *    party witness.
 * 2. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.  The
 *    redistribution must also be approved in writing from an official
 *    of Infinity Learning Solutions and a third party witness.
 * 3. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *    
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ''AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 */

package com.digitalchalk.apiexamples.service;

import java.io.*;
import java.net.*;
import java.security.cert.*;
import java.util.*;

import javax.net.ssl.*;

import org.apache.http.*;
import org.apache.http.client.methods.*;
import org.apache.http.client.utils.*;
import org.apache.http.conn.ssl.*;
import org.apache.http.entity.*;
import org.apache.http.impl.client.*;
import org.springframework.beans.factory.annotation.*;
import org.springframework.stereotype.*;

import com.digitalchalk.apiexamples.model.*;
import com.fasterxml.jackson.core.*;
import com.fasterxml.jackson.databind.*;

@Service
public class ApiService {
	
	@Autowired
	private ApiConfig apiConfig;
	
	/**
	 *  set this to true if you get SSL non-trusted certificate errors.  Should always be false in production environment
	 */
	private boolean usingSelfSignedCertificates = true; 
	
	private ObjectMapper mapper = new ObjectMapper();
	
	public ApiResult apiPut(String path, Object putData) {

		URI uri = makeUri(path,null);
		
		HttpPut httpPut = new HttpPut(uri);
		
		return executeRequest(httpPut, putData);

	}
	
	public ApiResult apiPost(String path, Object postData) {
			
		URI uri = makeUri(path,null);
		
		HttpPost httpPost = new HttpPost(uri);
		
		return executeRequest(httpPost, postData);
	}
	
	public ApiResult apiDelete(String path) {

			
		URI uri = makeUri(path, null);
		
		HttpDelete httpDelete = new HttpDelete(uri);
		
		return executeRequest(httpDelete, null);
						
	}

	
	public ApiResult apiGet(String path, Map<String,String> parameters) {
			
		URI uri = makeUri(path, parameters);
		
		HttpGet httpGet = new HttpGet(uri);
		
		return executeRequest(httpGet, null);
			
	}
	
	/**
	 * 
	 * Executes the httpRequest on the API and returns an ApiResult
	 * 
	 * @param request - the GET, POST, PUT, or DELETE to execute
	 * @param outData - data to write on stdout with the request (usually only POST or PUT have this)
	 * @return the ApiResult object
	 */
	private ApiResult executeRequest(HttpUriRequest request, Object outData) {
		CloseableHttpResponse response = null;
		CloseableHttpClient httpClient = null;
		try {			
			httpClient = makeHttpClient();
			
			if(outData != null && request instanceof HttpEntityEnclosingRequest) {
				try {
					String jsonData = mapper.writeValueAsString(outData);
					((HttpEntityEnclosingRequest)request).setEntity(new StringEntity(jsonData));
				} catch(JsonProcessingException jpex) {
					jpex.printStackTrace();
				}
			}
			
			request.addHeader("Accept", "application/json");
			request.addHeader("Content-type", "application/json");
			request.addHeader("Authorization", "Bearer " + apiConfig.getApiToken());
			response = httpClient.execute(request);
			
			return parseApiResult(response);
			
		} catch(Exception e) {
			
			e.printStackTrace();
			ApiResult errResult = new ApiResult();
			if(response != null) {
				errResult.setStatusCode(response.getStatusLine().getStatusCode());
			} else {
				errResult.setStatusCode(0);
			}
			errResult.setError(e.getClass().getSimpleName());
			errResult.setErrorDescription(e.getMessage());
			return errResult;
			
		} finally {

			if(response != null) {
				try {
					response.close();
				} catch(Exception ignoreMe) {
					
				}
			}
			
			if(httpClient != null) {
				try {
					httpClient.close();
				} catch(Exception ignoreMe1) {
					
				}
			}
		}
	}
	
	@SuppressWarnings("unchecked")
	private ApiResult parseApiResult(CloseableHttpResponse response) {
		ApiResult result = new ApiResult();
		
		if(response != null) {
			
			Header locationHeaders[] = response.getHeaders("Location");
			if(locationHeaders != null && locationHeaders.length > 0) {
				String locationHeader = locationHeaders[0].getValue();
				String parts[] = locationHeader.split("/");
				result.setLocationId(parts[parts.length-1]);
			}
			
			result.setStatusCode(response.getStatusLine().getStatusCode());
			result.setStatusLine(response.getStatusLine().toString());
			
			HttpEntity entity = response.getEntity();
			OutputStream os = new ByteArrayOutputStream();
			if(entity != null) {
				try {
					entity.writeTo(os);
					try {
						Map<String,Object> jsonMap = mapper.readValue(os.toString(), Map.class);
						result.setRawJson(os.toString());
						
						if(jsonMap.containsKey("results")) {
							result.setResults((List<HashMap>) jsonMap.get("results"));					
						}  else {
							List<HashMap> tempResults = new ArrayList<HashMap>();
							tempResults.add((HashMap)jsonMap);
							result.setResults(tempResults);
						}
						
						if(jsonMap.containsKey("errors")) {
							result.setError(((List)jsonMap.get("errors")).toString());
						}
						
						if(jsonMap.containsKey("error")) {
							result.setError((String)jsonMap.get("error"));
						}
						
						if(jsonMap.containsKey("fieldErrors")) {
							result.setFieldErrors((LinkedHashMap)jsonMap.get("fieldErrors"));				
						}
					} catch(JsonMappingException jmex) {
						// ignore, probably just "No content" response
					}
						
				} catch(IOException ioe) {
					ioe.printStackTrace();
					result.setError(ioe.getClass().getSimpleName());
					result.setErrorDescription(ioe.getMessage());
				}
			}
			
		}
		
		return result;
	}
	
	/**
	 * Build the URI
	 */
	private URI makeUri(String path, Map<String,String> parameters) {
		
		URIBuilder uriBuilder = new URIBuilder()
		.setScheme("https")
		.setHost(apiConfig.getApiHostname())
		.setPath(path)
		.setPort(apiConfig.getApiPort());

		if(parameters != null && !parameters.isEmpty()) {
			for(String param : parameters.keySet()) {
				uriBuilder.addParameter(param, parameters.get(param));
			}
		}
		try {
			URI uri = uriBuilder.build();
			return uri;
		} catch(URISyntaxException usex) {
			usex.printStackTrace();
			return null;
		}
	}
	
	
	/**
	 * Helps to eliminate SSL errors if you are using self-signed certificates.
	 */
	private CloseableHttpClient makeHttpClient() {
		HttpClientBuilder builder = HttpClientBuilder.create();
		if(this.usingSelfSignedCertificates) {
			builder.setHostnameVerifier(new X509HostnameVerifier() {
				
				@Override
				public boolean verify(String arg0, SSLSession arg1) {						
					return true;
				}

				@Override
				public void verify(String arg0, SSLSocket arg1) throws IOException {						
					
				}

				@Override
				public void verify(String arg0, X509Certificate arg1) throws SSLException {						
					
				}

				@Override
				public void verify(String arg0, String[] arg1, String[] arg2) throws SSLException {						
					
				}

			});
		}
		
		return builder.build();
	}

}
