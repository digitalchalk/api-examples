<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
 <%@ taglib prefix="fn" uri="http://java.sun.com/jsp/jstl/functions" %>
<html>
<head>
<title>Delete User Result</title>
<jsp:include page="/WEB-INF/jsp/common/inccss.jsp"/>
</head>
<body>
	<div class="container">
		<div class="span-24">
			<h2>Delete User Results</h2>
		</div>
		<c:choose>
		<c:when test="${apiResult.statusCode == 204 }">
		<div class="span-24">
			<div class="success">
			Success - Deleted
			</div>
		</div>
		</c:when>
		<c:otherwise>
		<jsp:include page="/WEB-INF/jsp/common/errorblock.jsp" flush="true"/>	
		</c:otherwise>
		</c:choose>
		<jsp:include page="/WEB-INF/jsp/common/rawdatablock.jsp" flush="true"/>	
	</div>
</body>
</html>		