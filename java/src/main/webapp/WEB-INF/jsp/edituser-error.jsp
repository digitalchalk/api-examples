<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
 <%@ taglib prefix="fn" uri="http://java.sun.com/jsp/jstl/functions" %>
<html>
<head>
<title>Edit User Error</title>
<jsp:include page="/WEB-INF/jsp/common/inccss.jsp"/>
</head>
<body>
	<div class="container">
		<div class="span-24">
			<h2>Edit User Error</h2>
		</div>
		<div class="span-24">
			<div class="error">
				<p>No user found with id ${id}</p>
				<p>Go back to the <a href="<c:url value='index.html'/>">Home</a> page</p>
			</div>
		</div>
	</div>
</body>
</html>