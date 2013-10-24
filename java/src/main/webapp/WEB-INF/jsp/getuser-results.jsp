<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
 <%@ taglib prefix="fn" uri="http://java.sun.com/jsp/jstl/functions" %>
<html>
<head>
<title>Get User(s) Result</title>
<jsp:include page="/WEB-INF/jsp/common/inccss.jsp"/>
</head>
<body>
	<div class="container">
		<div class="span-24">
			<h2>Get User(s) Results</h2>
		</div>
		<c:choose>
		<c:when test="${apiResult.statusCode == 200 }">
		<div class="span-24">
			<div class="success">
			Success
			</div>
		</div>
		<div class="span-4">
			<p>Results (${fn:length(apiResult.results) })</p>
		</div>
		<div class="span-20 last">
			<c:if test="${!empty apiResult.results }">
			<c:forEach items="${apiResult.results }" var="result">
				<c:set var="user" value="${result }" scope="request"/>
				<jsp:include page="/WEB-INF/jsp/common/showuser.jsp" flush="true"/>
			</c:forEach>
			</c:if>			
		</div>
		</c:when>
		<c:otherwise>
		<div class="span-24">
			<div class="error">
			Error
			<c:if test="${!empty apiResult.error }">
			<br>${apiResult.error }
			</c:if>
			<c:if test="${!empty apiResult.errorDescription }">
			<br>${apiResult.errorDescription }
			</c:if>	
			<c:if test="${!empty apiResult.fieldErrors }">
			<br>Field Errors
			<ul>
			<c:forEach items="${apiResult.fieldErrors }" var="fieldError">
				<li>${fieldError }</li>
			</c:forEach>
			</ul>
			</c:if>				
			</div>
		</div>
		</c:otherwise>
		</c:choose>
		<div class="span-24">
			<p>
				<a href="javascript:void(0);" onclick="window.history.back();">Back</a><br/><a href="<c:url value='index.html'/>">Back to home</a>
			</p>
		</div>
		<hr>
		<c:if test="${!empty apiResult.rawJson }">
			<div class="span-24">
				<p>Raw Data <a href="javascript:void(0);" onclick="$('#rawdata').toggle();">Toggle</a></p>
				<div id="rawdata" style="display:none">
				<p>Status Code: ${apiResult.statusCode }</p>
				<pre>${apiResult.rawJson }</pre>
				</div>
			</div>
		</c:if>

	</div>
</body>
</html>