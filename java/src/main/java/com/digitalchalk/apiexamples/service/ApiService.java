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
import org.apache.http.impl.client.*;
import org.springframework.beans.factory.annotation.*;
import org.springframework.stereotype.*;

import com.digitalchalk.apiexamples.model.*;
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
	
	public ApiResult apiGet(String path, Map<String,String> parameters) {
		CloseableHttpResponse response = null;
		CloseableHttpClient httpClient = null;
		try {			
			httpClient = makeHttpClient();
			
			
			
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
			
			URI uri = uriBuilder.build();
			
			HttpGet httpGet = new HttpGet(uri);
			

			
			httpGet.addHeader("Accept", "application/json");
			httpGet.addHeader("Content-type", "application/json");
			httpGet.addHeader("Authorization", "Bearer " + apiConfig.getApiToken());
			response = httpClient.execute(httpGet);
			
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
			
			result.setStatusCode(response.getStatusLine().getStatusCode());
			
			HttpEntity entity = response.getEntity();
			OutputStream os = new ByteArrayOutputStream();
			try {
				entity.writeTo(os);
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
					result.setFieldErrors((List)jsonMap.get("fieldErrors"));				
				}
				
			} catch(IOException ioe) {
				ioe.printStackTrace();
				result.setError(ioe.getClass().getSimpleName());
				result.setErrorDescription(ioe.getMessage());
			}
			
		}
		
		return result;
	}
	
	/**
	 * Debug only
	 */
	private void dumpMap(Map<String,Object> toDump) {
		if(toDump == null) {
			return;
		}
		for(String key : toDump.keySet()) {
			System.out.println(key + " => " + toDump.get(key));
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
