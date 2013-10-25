<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
 <%@ taglib prefix="fn" uri="http://java.sun.com/jsp/jstl/functions" %>
<html>
<head>
<title>Get User(s) Result</title>
<jsp:include page="/WEB-INF/jsp/common/inccss.jsp"/>
</head>
<script type="text/javascript">
function deleteUser(userId) {
	alertify.confirm("Delete user?", function(e) {
		if(e) {
			window.location.href = 'deleteuser.html?id=' + userId;
		} 
	});
}

function editUser(userId) {
	window.location.href = 'edituser.html?id=' + userId;
}
</script>
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
			<c:choose>			
			<c:when test="${!empty apiResult.results }">
			<c:forEach items="${apiResult.results }" var="result">
				<c:set var="user" value="${result }" scope="request"/>
				<jsp:include page="/WEB-INF/jsp/common/showuser.jsp" flush="true"/>
			</c:forEach>
			</c:when>
			<c:otherwise>
				<p>The API call was successful, but no results were found</p>
			</c:otherwise>
			</c:choose>			
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