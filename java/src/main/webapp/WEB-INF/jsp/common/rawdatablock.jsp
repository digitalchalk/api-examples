<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
		<div class="span-24">
			<p>
				<a href="javascript:void(0);" onclick="window.history.back();">Back</a><br/><a href="<c:url value='index.html'/>">Home</a>
			</p>
		</div>
		<hr>
		
		<div class="span-24">
			<p>Raw Data <a href="javascript:void(0);" onclick="$('#rawdata').toggle();">Toggle</a></p>
			<div id="rawdata" style="display:none">				
			<p>Status Code: ${apiResult.statusCode }</p>
			<p>Status Line: ${apiResult.statusLine }</p>
			<c:if test="${!empty apiResult.rawJson}"><pre>${apiResult.rawJson }</pre></c:if>
			</div>
		</div>
